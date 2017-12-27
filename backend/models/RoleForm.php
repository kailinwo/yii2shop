<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 17/12/27
 * Time: 13:56
 */
namespace backend\models;
use yii\base\Model;

class RoleForm extends Model
{
    //字段
    public $name;
    public $description;
    public $permission;
    //验证
    public function rules()

    {
        return [
            [['name','description'],'required'],
            ['permission','safe'],
        ];
    }
    //字段中文
    public function attributeLabels()
    {
        return [
            'name'=>'角色名称',
            'description'=>'角色描述',
            'permission'=>'此角色的权限',
        ];
    }
}