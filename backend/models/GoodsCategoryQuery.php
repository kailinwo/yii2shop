<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 17/12/22
 * Time: 10:46
 */
namespace backend\models;
use creocoder\nestedsets\NestedSetsQueryBehavior;
class GoodsCategoryQuery extends \yii\db\ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}