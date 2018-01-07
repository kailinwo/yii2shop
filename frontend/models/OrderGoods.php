<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 18/1/7
 * Time: 17:04
 */

namespace frontend\models;
use yii\db\ActiveRecord;

class OrderGoods extends ActiveRecord
{
    public function rules()
    {
        return [
            [['order_id', 'goods_id', 'goods_name', 'logo', 'price', 'amount', 'total'], 'required']
        ];
    }
}