<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\data\Pagination;
use yii\helpers\Json;

class GoodsCategoryController extends \yii\web\Controller
{
    //商品分类的首页
    public function actionIndex()
    {
        //查询表数据
        $query = GoodsCategory::find();
        $pager = new Pagination([
         'totalCount'=>$query->count(),//总数据
         'defaultPageSize'=>5//每页多少条
        ]);
        $model = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['model'=>$model,'pager'=>$pager]);
    }
    //商品分类的添加
    public function actionAdd(){
        $model = new GoodsCategory();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            //根据有没有父类id来判断是否是根节点
            if($model->parent_id){
                //父类id不为零就是子分类
                //>>去查找上一它对应的上一级id
                $model->appendTo(GoodsCategory::findOne(['id'=>$model->parent_id]));
            }else{
                //为0就是根节点
                $model->makeRoot();
            }
            //添加完成之后就提示信息>>跳转
            \Yii::$app->session->setFlash('success','添加分类成功!');
            return $this->redirect(['goods-category/index']);
        }

        return $this->render('add',['model'=>$model]);
    }
    //zTree的demo演示
    public function actionShow(){
        //yii里面是直接加载了layouts的文件的,所以使用renderPartial只显示视图里面的!
        return $this->renderPartial('show');

    }

    //分类信息的修改
    public function actionUpdate($id){
        $model = GoodsCategory::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            if($model->parent_id){
                //父类id不为零就是子分类
                //>>去查找上一它对应的上一级id
                $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                $model->appendTo($parent);
            }else{
                //为0就是根节点
                if($model->getOldAttribute('parent_id')){
                    $model->makeRoot();
                }else{
                    $model->save();
                }
            }
            //提示信息
            \Yii::$app->session->setFlash('success','修改分类信息成功!');
            //跳转
            return $this->redirect(['goods-category/index']);
        }
        return $this->render('add',['model'=>$model]);
    }
    //分类信息的删除
    public function actionDelete($id){
        //根据传过来的id去扫表看看是否有这个id相同的parent_id
        $model = GoodsCategory::find()->where(['parent_id'=>$id])->all();
        if($model){
            //提示信息>>>该分类下有子节点是否确认删除?
//            var_dump($info);die;
            echo 1;
        }else{
            //该分类下没有子分类;>>静默删除
//            echo json_encode(0);
            GoodsCategory::deleteAll(['id'=>$id]);
        }
    }


}
