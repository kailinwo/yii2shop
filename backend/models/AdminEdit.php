<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 17/12/28
 * Time: 16:14
 */
namespace backend\models;
class AdminEdit extends Admin{
    //定义字段
    public $old_password_hash;
    public $password_hash;
    public $re_password_hash;
    //规则
    public function rules()
    {
        return [
            [['old_password_hash','password_hash','re_password_hash'],'required'],
            //新密码与旧密码作比较
            ['re_password_hash', 'compare', 'compareAttribute' =>'password_hash'],
        ];
    }
    //字段中文名
    public function attributeLabels()
    {
        return [
            'old_password_hash'=>'旧密码',
            'password_hash'=>'新密码',
            're_password_hash'=>'确认密码'
        ];
    }
}