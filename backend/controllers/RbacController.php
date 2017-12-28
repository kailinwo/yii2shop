<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 17/12/26
 * Time: 21:20
 */
namespace backend\controllers;
use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\rbac\Permission;
use yii\rbac\Role;
use yii\web\Controller;

class RbacController extends Controller
{
    //权限添加
    public function actionPermissionAdd(){
        $model = new PermissionForm();//创建表单对象;
        //给当前的表单添加一个场景让验证规则生效
        $model->scenario = PermissionForm::SCENARIO_PERMISSION_ADD;
        if(\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if($model->validate()){
                $model->insert();//调用表单模型来添加
                \Yii::$app->session->setFlash('success','添加权限成功!');
                return $this->redirect(['rbac/permission-index']);
            }
        }
        return $this->render('permission-add',['model'=>$model]);
    }
    //权限首页
    public function actionPermissionIndex(){
        //创建rbac的对象->调用对象上面的方法得到所有创建的权限
        $model = \Yii::$app->authManager->getPermissions();
        return $this->render('permission-index',['model'=>$model]);
    }
    //权限删除
    public function actionPermissionDelete($name){
        $authManager = \Yii::$app->authManager;//创建新的权限对象
        $permission=$authManager->getPermission($name);//找到那个对象!
        \Yii::$app->authManager->remove($permission);
    }
    //权限修改
    public function actionPermissionUpdate($name){
        $authManager = \Yii::$app->authManager;//创建新的权限对象
        $Permission = $authManager->getPermission($name);
        $model = new PermissionForm();//创建表单对象;
        $model->scenario = PermissionForm::SCENARIO_PERMISSION_UPDATE;
        $model->name = $Permission->name; //名称显示到页面上
        $model->description = $Permission->description;//名称显示到页面
        if(\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());//加载表单数据
            if($model->validate()){//验证数据不能忘
                $model->update($name);
                //提示信息
                \Yii::$app->session->setFlash('success','修该权限成功!');
                return $this->redirect(['rbac/permission-index']);
            }
        }
        return $this->render('permission-add',['model'=>$model]);
    }
    //角色添加
    public function actionRoleAdd(){
        $model = new RoleForm();//创建表单对象
        $authManager = \Yii::$app->authManager;//管理对象
        if(\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if($model->validate()){
                $role = new Role();//角色对象
                $role->name = $model->name; //赋值name
                $role->description = $model->description;//赋值 description
                $authManager->add($role);//保存角色
                //+++++权限的添加处理+++++
                //因为提交过来的是数组,数据表里面是一个角色对应多个权限,一条一条的存储;
                if($model->permission){
                    foreach ($model->permission as $per){
                        $permission = $authManager->getPermission($per);
                        $authManager->addChild($role,$permission);//有几个就添加几个权限addChild
                        echo 1;
                    }
                }
                //+++++权限的添加处理+++++
                \Yii::$app->session->setFlash('success','添加角色成功!');
                return $this->redirect(['rbac/role-index']);
            }
        }
        //++++得到所有的权限+++++
        $permissions = $authManager->getPermissions();
        $permission=[];  //准备空数组来存放保存的key=>value形式的数据
        foreach($permissions as $per){ //遍历为:key=>value
            $permission[$per->name] = $per->description;
        }
        //++++++得到所有的权限+++++
        return $this->render('role-add',['model'=>$model,'permission'=>$permission]);
    }
    //角色首页
    public function actionRoleIndex(){
        $authManager = \Yii::$app->authManager;
        $model = $authManager->getRoles();
        return $this->render('role-index',['model'=>$model]);
    }
    //角色修改
    public function actionRoleUpdate($name){
        $authManger = \Yii::$app->authManager;
        $role = $authManger->getRole($name);
        //var_dump($role);die;
        $model = new RoleForm();
        $model->name = $role->name;
        $model->description = $role->description;
        //+++++处理权限的回显+++++
        $model->permission = [];
        //根据当前的角色来获取当前角色所拥有的权限
        //var_dump($model);die;
        $permissions = $authManger->getPermissionsByRole($name);
        foreach($permissions as $permission){
            $model->permission[] = $permission->name;
        }
//        var_dump($model);die;
        //++++得到所有的权限+++++
        $permissions = $authManger->getPermissions();
        $permission=[];  //准备空数组来存放保存的key=>value形式的数据
        foreach($permissions as $per){ //遍历为:key=>value
            $permission[$per->name] = $per->description;
        }
        //======修改后提交=========
        if(\Yii::$app->request->isPost){
             $model->load(\Yii::$app->request->post());
             if($model->validate()){
                 $role->name = $model->name;
                 $role->description = $model->description;
                 $authManger->update($name,$role);
                 //1.1再保存修改的权限前,先去除所有的权限
                 $authManger->removeChildren($role);
                 //1.2重新关联该角色的新的权限
                 foreach($model->permission as $per){
                     $permission=$authManger->getPermission($per);
                     $authManger->addChild($role,$permission);
                 }
                 //保存成功提交
                 \Yii::$app->session->setFlash('success','修改角色成功!');
                 return $this->redirect(['rbac/role-index']);
             }
        }
        return $this->render('role-add',['model'=>$model,'permission'=>$permission]);
    }
    //角色删除
    public function actionRoleDelete($name){
        $auth = \Yii::$app->authManager;
        $role = $auth->getRole($name);
        $auth->remove($role);
    }
}