<?php

use yii\db\Migration;

/**
 * Handles the creation of table `member`.
 */
class m180102_133839_create_member_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('member', [
            'id' => $this->primaryKey()->comment('主键ID'),
            'username'=>$this->string(50)->comment('用户名'),
            'auth_key'=>$this->string()->comment('授权键'),
            'password_hash'=>$this->string()->comment('密码'),
            'email'=>$this->string()->comment('电子邮箱'),
            'tel'=>$this->char(11)->comment('电话'),
            'last_login_time'=>$this->integer()->comment('最后登录时间'),
            'last_login_ip'=>$this->integer()->comment('最后登录ip'),
            'status'=>$this->integer(1)->comment('状态 (1正常,0删除)'),
            'create_at'=>$this->integer()->comment('添加时间'),
            'update_at'=>$this->integer()->comment('修改时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('member');
    }
}
