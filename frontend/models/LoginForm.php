<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 18/1/3
 * Time: 0:21
 */

namespace frontend\models;


use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password_hash;
    public $remmber;
    public $checkcode;
    //验证规则
    public function rules()
    {
        return [
            [['username','password_hash'],'required'],
            ['checkcode','captcha','captchaAction'=>'site/captcha'],
            ['remmber','safe'],
        ];

    }
}