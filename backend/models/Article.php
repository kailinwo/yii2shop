<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 17/12/20
 * Time: 22:51
 */
namespace backend\models;
use yii\db\ActiveRecord;

class Article extends ActiveRecord{

    public function rules()
    {
        return [
            [['name','intro','article_category_id','sort','status'],'required'],
            [['sort'],'number'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'名称',
            'intro'=>'描述',
            'article_category_id'=>'分类',
            'sort'=>'排序',
            'status'=>'状态'
        ];
    }
    //查询文章分类表的名字,显示到这个文章页面上面来
    public function getArticleCategory()
    {
        return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id']);
    }
}