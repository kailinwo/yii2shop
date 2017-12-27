<?php

use yii\db\Migration;

/**
 * Handles the creation of table `admin`.
 */
class m171225_062619_create_admin_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('admin', [
            'id' => $this->primaryKey()->comment('主键id'),
            'username'=>$this->string()->comment('用户名'),
            'auth_key'=>$this->string()->comment('认证密钥'),
            'password_hash'=>$this->string()->comment('密码'),
            'password_reset_token'=>$this->string()->comment('密码重置口令'),
            'email'=>$this->string()->comment('电子邮箱'),
            'status'=>$this->smallInteger(6)->comment('商品状态'),
            'create_at'=>$this->integer()->comment('创建时间'),
            'update_at'=>$this->integer()->comment('更新时间'),
            'last_login_time'=>$this->integer()->comment('最后登录的时间'),
            'last_login_ip'=>$this->integer()->comment('最后登录ip'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('admin');
    }
}
