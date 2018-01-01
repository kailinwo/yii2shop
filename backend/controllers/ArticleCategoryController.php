<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 17/12/20
 * Time: 18:55
 */
namespace backend\controllers;
use backend\filters\RbacFilter;
use backend\models\ArticleCategory;
use yii\web\Controller;

class ArticleCategoryController extends Controller{
    //文章分类的首页
    public function actionIndex()
    {
        $model = ArticleCategory::find()->where(['>=','status','0'])->all();
        //显示页面
        return $this->render('index',['model'=>$model]);
    }
    //文章分类的添加
    public function actionAdd()
    {
        //创建新的提交事件
        $request=\Yii::$app->request;
        $model= new ArticleCategory();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                //提示信息
                \Yii::$app->session->setFlash('success','添加文章分类成功!');
                //跳转
                return $this->redirect(['article-category/index']);
            }
        }
        $model->status=1;
        //显示页面
        return $this->render('add',['model'=>$model]);
    }
    //文章分类的修改
    public function actionUpdate($id)
    {
        //创建新的提交事件
        $request=\Yii::$app->request;
        $model= ArticleCategory::findOne(['id'=>$id]);
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                //提示信息
                \Yii::$app->session->setFlash('success','修改文章分类成功!');
                //跳转
                return $this->redirect(['article-category/index']);
            }
        }
        //显示页面
        return $this->render('add',['model'=>$model]);
    }
    //文章分类的删除
    public function actionDelete($id)
    {
        ArticleCategory::updateAll(['status'=>-1],['id'=>$id]);
    }
    //根据用户的权限来控制用户能调用的方法;
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ],
        ];
    }
}