<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 17/12/29
 * Time: 10:51
 */

namespace backend\models;
use yii\db\ActiveRecord;

class Menu extends ActiveRecord
{
    public function rules()
    {
        return [
            [['name','parent_id','url'],'required'],
            ['sort','safe'],
        ];
    }
    public function attributeLabels()
    {
        return[
            'name'=>'菜单名称',
            'parent_id'=>'上级菜单',
            'url'=>'地址/路由',
            'sort'=>'排序',
        ];
    }
}