<?php

namespace backend\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "admin".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property integer $last_login_time
 * @property integer $last_login_ip
 */
class Admin extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $old_password_hash;
    public $re_password_hash;
    public $role;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password_hash', 'email','status'], 'required'],
            [['email'],'email'],
            ['re_password_hash', 'compare', 'compareAttribute' =>'password_hash'],
            [['re_password_hash','old_password_hash','role'],'safe'],
        ];
    }
    //验证旧密码
    public function verifypwd(){
        $admin=Admin::findOne(['username'=>$this->username]);
//        var_dump($admin);die;
        $dbpassword = $admin->password_hash;
        if($admin){
            if(\Yii::$app->security->validatePassword($this->old_password_hash,$dbpassword)){
                return true;
            }else{
                return false;
            }
        };
        return false;
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'password_hash' => '新密码',
            'old_password_hash'=>'旧密码',
            're_password_hash'=>'确认密码',
            'email' => '邮箱',
            'status' => '状态',
            'role'=>'角色',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最后登录ip',
        ];
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        // TODO: Implement findIdentity() method.
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        // TODO: Implement getId() method.
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
        return $this->getAuthKey() === $authKey;
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = \Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }

    //得到用户的菜单
    public function getMenus(){
        $menuItems = [];
        $menus = Menu::find()->where(['parent_id'=>0])->all();
        foreach ($menus as $menu){
            $children = Menu::find()->where(['parent_id'=>$menu->id])->all();
            $items = [];
            foreach($children as $child){
                //判断用户是否拥有某权限,根据权限来完成该用户的菜单显示
                if(Yii::$app->user->can($child->url)){
                    $items[] = ['label'=>$child->name,'url'=>[$child->url]];
                }
            }
            if($items){
                $menuItems[] = ['label'=>$menu->name,'items'=>$items];
            }
        }
        return $menuItems;
    }

}
