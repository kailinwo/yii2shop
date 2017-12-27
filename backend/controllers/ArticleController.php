<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 17/12/20
 * Time: 22:55
 */
namespace backend\controllers;
use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;
use common\widgets\ueditor\Ueditor;
use common\widgets\ueditor\UeditorAction;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class ArticleController extends Controller{
    //文章的首页
    public function actionIndex()
    {
//        $model1= ArticleDetail::find()->all();
        $model = Article::find()->where(['>=','status','0'])->all();
        return $this->render('index',['model'=>$model]);
    }
    //文章的添加
    public function actionAdd()
    {
        //创建提交对象
        $request= \Yii::$app->request;
        //创建model对象
        $model = new Article();
        $model1 = new ArticleDetail();
        if($request->isPost){
            $model->load($request->post());
            $model1->load($request->post());
            if ($model->validate()){
                $model->create_time= time();
                $model->save();
                //给文章表添加成功之后给detail表保存id和content
                $model1->article_id=\Yii::$app->db->getLastInsertID() ;
                $model1->save();
                //输出信息并且跳转
                \Yii::$app->session->setFlash('success','添加文章成功!');
                return $this->redirect(['article/index']);
            }
        }
        //显示页面
        $model ->status=1;
        //查询category表里面的数据>>输出到下拉表选择框里面
        $article_category= ArticleCategory::find()->all();
        $category=ArrayHelper::map($article_category,'id','name');
        return $this->render('add',['model'=>$model,'categorys'=>$category,'model1'=>$model1]);
    }
    //文章的修改
    public function actionUpdate($id)
    {
        //创建提交对象
        $request= \Yii::$app->request;
        //创建model对象
        $model = Article::findOne(['id'=>$id]);
        $model1 = ArticleDetail::findOne(['article_id'=>$id]);
        if($request->isPost){
            $model->load($request->post());
            $model1->load($request->post());
            if ($model->validate()){
                $model->save();//保存文章表
                $model1->save();//保存文章detail表;
                //输出信息并且跳转
                \Yii::$app->session->setFlash('success','修改文章成功!');
                return $this->redirect(['article/index']);
            }
        }
        //显示页面
        //查询category表里面的数据>>输出到下拉表选择框里面
        $article_category= ArticleCategory::find()->all();
        $category=ArrayHelper::map($article_category,'id','name');
        return $this->render('add',['model'=>$model,'categorys'=>$category,'model1'=>$model1]);
    }
    //文章的删除
    public function actionDelete($id)
    {
        //删除,该状态
        Article::updateAll(['status'=>-1],['id'=>$id]);
    }
    //富文本编辑器'common\widgets\ueditor\UeditorAction'
    public function actions(){

        return [
            'ueditor'=>[
                'class' =>UeditorAction::className(),
                'config'=>[
                    //上传图片配置
                    'imageUrlPrefix' => "", /* 图片访问路径前缀 */
                    'imagePathFormat' => "/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
                ]
            ]
        ];
    }
}