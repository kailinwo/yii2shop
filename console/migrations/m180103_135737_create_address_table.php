<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m180103_135737_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey()->comment('主键id'),
            'name'=>$this->string()->comment('收货人姓名'),
            'cmbProvince'=>$this->string()->comment('省'),
            'cmbCity'=>$this->string()->comment('市'),
            'cmbArea'=>$this->string()->comment('县'),
            'address'=>$this->string()->comment('详细地址'),
            'tel'=>$this->char(11)->comment('手机号码'),
            'member_id'=>$this->integer()->comment('用户id'),
            'status'=>$this->integer(1)->comment('1为默认地址,0为普通地址'),
            'create_at'=>$this->integer()->comment('创建时间'),
            'update_at'=>$this->integer()->comment('修改时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
