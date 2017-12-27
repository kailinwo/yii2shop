<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 17/12/25
 * Time: 17:46
 */
namespace backend\models;
class AdminUpdate extends \backend\models\Admin
{
    public $old_password_hash;
    public $re_password_hash;
//    public $status;

    //验证规则
    public function rules()
    {
        return [
            [['password_hash','old_password_hash','re_password_hash','username','email'],'required'],
            ['re_password_hash', 'compare', 'compareAttribute' =>'password_hash'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'old_password_hash'=>'旧密码',
            're_password_hash'=>'重复密码',
            'username'=>'用户名',
            'password_hash'=>'新密码',
            'status'=>'状态',
            'email'=>'邮箱',
        ];
    }
}