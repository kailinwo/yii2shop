<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 17/12/20
 * Time: 14:19
 */
namespace backend\models;
use yii\db\ActiveRecord;

class Brands extends ActiveRecord{
    public function rules()
    {
        return [
            [['name','intro','logo','status'],'required'],
            [['sort'],'number'],
        ];
    }

    public function attributeLabels()
    {
        return[
           'name'=>'名称',
           'intro'=>'简介',
           'logo'=>'LOGO',
           'sort'=>'排序',
           'status'=>'状态'
        ];
    }
}