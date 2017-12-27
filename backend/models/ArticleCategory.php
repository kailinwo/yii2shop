<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 17/12/20
 * Time: 18:52
 */
namespace backend\models;
use yii\db\ActiveRecord;

class ArticleCategory extends ActiveRecord{


    public function rules()
    {
        return [
            [['name','intro','sort','status'],'required'],
            ['sort','number'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>'名称',
            'intro'=>'简介',
            'sort'=>'排序',
            'status'=>'状态'
        ];
    }
}