<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 18/1/1
 * Time: 21:35
 */

namespace frontend\controllers;
use frontend\models\Goods;
use frontend\models\GoodsGallery;
use frontend\models\GoodsIntro;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class GoodsController extends Controller
{
    //goodslist->one->two->three
    public function actionList($id){
        //search for datebase
        $cat = \backend\models\GoodsCategory::findOne(['id'=>$id]);
        //choose depth on two
        if($cat->depth==2){
            $ids = [$id];
        }else{
            $categorys = $cat->children()->select('id')->andWhere(['depth'=>2])->asArray()->all();
            $ids = ArrayHelper::map($categorys,'id','id');
        }
        //get three goodsCategorys
        //create pagernation chain 根据集合(in [3,4,5])来写sql语句;
        $total = \backend\models\Goods::find()->where(['in','goods_category_id',$ids])->count();
        $pager = new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>3,
        ]);
        $model=Goods::find()->limit($pager->limit)->offset($pager->offset)->where(['goods_category_id'=>$ids])->all();
        return $this->render('list',['model'=>$model,'pager'=>$pager]);
    }
    public function actionDetails($id){
        $goodsinfo = Goods::findOne(['id'=>$id]);
        $goodsdetails = GoodsIntro::findOne(['goods_id'=>$id]);
        $goodsGallery = GoodsGallery::find()->where(['goods_id'=>$id])->all();
        $goodsimage= min($goodsGallery);//得到第一张图片
//        var_dump($goodsGallery);die;
        //处理商品点击数
       $times = $goodsinfo['view_times'];
       if($times){
           $times +=1;
       }else{
           $times++;
       }
       Goods::updateAll(['view_times'=>$times],['id'=>$id]);
        return $this->render('goodsdetails',['goodsinfo'=>$goodsinfo,'goodsdetails'=>$goodsdetails,'goodsGallery'=>$goodsGallery,'goodsimage'=>$goodsimage]);
    }
}