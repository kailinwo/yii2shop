<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Admin;
use backend\models\LoginForm;
use yii\captcha\CaptchaAction;
use yii\db\Exception;
use yii\web\Request;

class AdminController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = Admin::find()->all();
        return $this->render('index',['model'=>$model]);
    }
    public function actionAdd(){
        //创建一个新的提交对象
        $request = new Request();
        $model = new Admin();
        $authManager = \Yii::$app->authManager;
        $roles = $authManager->getRoles();
        $role = [];
        foreach($roles as $rol){
            $role[$rol->name] =$rol->description;
        }
//        var_dump($role);die;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //开启事务
                $trans = \Yii::$app->db->beginTransaction();
                try{
                    $model->create_at = time();
                    //处理密码
                    $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);
                    if(!$model->save()){
                        throw new Exception(current($model->getFirstErrors()));
                    };
                    //给该用户添加角色>>得到用户的ID(getlastinsertid)
                    $data = [];
                    foreach($model->role as $role){
                        $data[] = [$role,$model->getPrimaryKey(),time()];
                    }
                    if(!\Yii::$app->db->createCommand()->batchInsert('auth_assignment', ['item_name','user_id','created_at'], $data)->execute()){
                        throw new Exception('关联角色失败!');
                    };
                    $trans->commit();//提交事务
                    //添加成功>>跳转
                    \Yii::$app->session->setFlash('success','添加管理员成功!');
                    return $this->redirect(['admin/index']);
                }catch(Exception $e){
                    $trans->rollBack();
                }
            }else{
                var_dump($model->getErrors());die;
            }
        }
        $model->status=1;
        return $this->render('add',['model'=>$model,'role'=>$role]);
    }
    //管理员的修改
    public function actionUpdate($id)
    {
        //新建查询对象
        $request = \Yii::$app->request;
        //根据id查询数据表
        $model =Admin::findOne(['id'=>$id]);
        //得到根据用户的id去找到该用户关联的role
        $authManager = \Yii::$app->authManager;
        $uroles = $authManager->getRolesByUser($id);
        $model->role = [];
        foreach($uroles as $roles){
            $model->role[] = $roles->name;
        }
        if($request->isPost) {
            //加载表单数据
            $model->load($request->post());
            if ($model->validate()) {
                //开启事务
                $trans = \Yii::$app->db->beginTransaction();
                try{
                    $model->update_at = time();
                    if(!$model->save()){
                        throw new Exception(current($model->getFirstErrors()));
                    };
                    //1.1再保存修改的用户的角色信息前,先去除所有的角色
                    $authManager->revokeAll($id);
                    //1.2再得到所有的获取的角色,一条一条的遍历添加
                    if($model->role){
                        foreach($model->role as $val){
                            $roles = $authManager->getRole($val);
                            $authManager->assign($roles,$id);
                        }
                    }
                    $trans->commit();//提交事务
                    //跳转和提示信息
                    \Yii::$app->session->setFlash('success','修改管理员信息成功!');
                    return $this->redirect(['admin/index']);
                }catch(Exception $e){
                    //事务回滚
                    $trans->rollBack();
                }
            }
        }
        //++++得到所有的角色+++++
        $role = [];
        $allroles = $authManager->getRoles();
        foreach($allroles as $rol){
            $role[$rol->name] = $rol->description;
        }
        return $this->render('update',['model'=>$model,'role'=>$role]);
    }
    //管理员的删除
    public function actionDelete($id){
        Admin::deleteAll(['id'=>$id]);
    }
    //验证码
    public function actions()
    {
        return [
            'captcha'=>[
                'class'=>CaptchaAction::className(),
                'height'=>40,
                'padding'=>0,
                'minLength'=>4,
                'maxLength'=>4,
            ]

        ];
    }
    //管理员登录
    public function actionLogin()
    {
        $request = new Request();
        $model= new LoginForm();
        if($request->isPost){
            $model->load($request->post());//加载表单里面的数据
//            var_dump($model);die;
            if($model->login()){
                //登录成功就提示信息>>跳转
                $session = \Yii::$app->user;
                Admin::updateAll(['last_login_time'=>time(),'last_login_ip'=>\Yii::$app->request->userIP],['id'=>$session->id]);
                \Yii::$app->session->setFlash('success','登录成功!');
                return $this->redirect(['admin/index']);
            }
        }
        return $this->render('login',['model'=>$model]);
    }
    //管理员注销
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect(['admin/login'])->send();
    }
    //修改自己的密码
    public function actionEdit(){
        //通过user组件得到用户的id
        $id = \Yii::$app->user->id;
        //根据id去查询数据库
        $model = Admin::findOne(['id'=>$id]);
        //旧密码
        $dbpassword = $model->password_hash;
//        var_dump($model->password_hash);die;
        //加载表单模型
//        $model = new AdminEdit();
        if(\Yii::$app->request->isPost){
            //加载表单数据
            $model->load(\Yii::$app->request->post());
            if($model->validate()){
//                var_dump($model);die;
                //验证旧密码是否正确
                if(\Yii::$app->security->validatePassword($model->old_password_hash,$dbpassword)){
                    //密码正确就给新密码加密保存
                    $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
                    $model->save(false);
                    //提示信息
                    \Yii::$app->session->setFlash('success','修改密码成功!');
                    return $this->redirect(['admin/index']);
                }else{
                    $model->addError('old_password_hash','旧密码不正确!');
                }
            }else{
                var_dump($model->getErrors());
            }
        }
        $model->password_hash = '';
        return $this->render('edit',['model'=>$model]);
    }
    //配置用户的权限
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
                'except'=>['login','logout','captcha'],
            ]
        ];
    }
}
