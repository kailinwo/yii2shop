<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 17/12/29
 * Time: 11:22
 */
namespace backend\controllers;
use backend\filters\RbacFilter;
use backend\models\Menu;
use yii\data\Pagination;
use yii\web\Controller;

class MenuController extends Controller
{
    //菜单添加
    public function actionAdd(){
        $model= new Menu();
        if(\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','添加菜单成功!');
                return $this->redirect(['menu/index']);
            }
        }
        //处理上级菜单
        $parent = Menu::find()->all();
        $par=[];
        array_unshift($par,'=请选择上级菜单=');
        foreach($parent as $val ){
            $par[$val->id] =$val->name;
        }
        //处理地址/路由
        $authManager = \Yii::$app->authManager;
        $permmissions = $authManager->getPermissions();
        $per=[];
        array_unshift($per,'=请选择地址/路由=');
        foreach ($permmissions as $permission){
            $per[$permission->name]=$permission->name;
        }
        return $this->render('add',['model'=>$model,'permission'=>$per,'parent'=>$par]);
    }
    //菜单首页
    public function actionIndex(){
        $query = Menu::find();
        $pager = new Pagination([
           'totalCount'=>$query->count(),
            'defaultPageSize'=>5
        ]);
        $model = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['model'=>$model,'pager'=>$pager]);
    }
    //菜单添加
    public function actionUpdate($id){
        $model=Menu::findOne(['id'=>$id]);
        if(\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','修改菜单成功!');
                return $this->redirect(['menu/index']);
            }
        }
        //处理上级菜单
        $parent = Menu::find()->all();
        $par=[];
        array_unshift($par,'=请选择上级菜单=');
        foreach($parent as $value){
            $par[$value->id]= $value->name;
        }
        //处理地址/路由
        $authManager = \Yii::$app->authManager;
        $permmissions = $authManager->getPermissions();
        $per=[];
        array_unshift($per,'=请选择地址/路由=');
        foreach ($permmissions as $permission){
            $per[$permission->name]=$permission->name;
        }
        return $this->render('add',['model'=>$model,'permission'=>$per,'parent'=>$par]);
    }
    //菜单删除
    public function actionDelete($id){
        Menu::deleteAll(['id'=>$id]);
    }
    //权限控制
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }
}