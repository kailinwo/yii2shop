<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 17/12/26
 * Time: 10:31
 */
namespace backend\models;
use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password_hash;
    public $code;
    public $remmber;

    public function rules(){
       return [
            [['username','password_hash','code'],'required'],
           ['remmber','safe'],
               ['code','captcha','captchaAction'=>'admin/captcha'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password_hash'=>'密码',
            'code'=>'验证码',
            'remmber'=>'使我保持登录状态',
        ];
    }
    //验证用户登录的方法
    public function login(){
        $admin = Admin::findOne(['username'=>$this->username]);
        if($admin){
            //如果这个用户存在就验证密码
            if(\Yii::$app->security->validatePassword($this->password_hash,$admin->password_hash)){
                //密码正确就能够登录>>保存session
                if($this->remmber){
                    \Yii::$app->user->login($admin,7*24*3600);
                }else{
                    \Yii::$app->user->login($admin);
                }
                return true;
            }else{
                $this->addError('password_hash','密码不正确');
            }
        }else{
            $this->addError('username','用户名不存在');
        }
        return false;
    }
}