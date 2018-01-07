<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 18/1/3
 * Time: 23:47
 */

namespace frontend\models;
use yii\db\ActiveRecord;

class Address extends ActiveRecord
{
    public function rules()
    {
        return [
            [['name','cmbProvince','cmbCity','cmbArea','address','tel'],'required'],
            ['status','safe'],
        ];
    }
}