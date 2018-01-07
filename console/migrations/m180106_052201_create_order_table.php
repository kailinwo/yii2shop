<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order`.
 */
class m180106_052201_create_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order', [
            'id' => $this->primaryKey()->comment('主键id'),
            'member_id'=>$this->integer()->comment('用户id'),
            'name'=>$this->string(50)->comment('收货人'),
            'province'=>$this->string(20)->comment('省份'),
            'city'=>$this->string(20)->comment('市'),
            'area'=>$this->string(20)->comment('县'),
            'address'=>$this->string()->comment('详细地址'),
            'tel'=>$this->char(11)->comment('手机号码'),
            'delivery_id'=>$this->integer()->comment('配送方式id'),
            'delivery_name'=>$this->string()->comment('配送方式的名称'),
            'delivery_price'=>$this->decimal(10,2)->comment('配送方式的费用'),
            'payment_id'=>$this->integer()->comment('支付方式的id'),
            'payment_name'=>$this->string()->comment('支付方式名字'),
            'total'=>$this->decimal(10,2)->comment('支付金额'),
            'status'=>$this->integer()->comment('订单状态,0已取消 1待付款 2待发货 3待收货 4完成'),
            'trade_no'=>$this->string()->comment('第三方支付交易号码'),
            'create_time'=>$this->integer()->comment('创建时间'),

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order');
    }
}
