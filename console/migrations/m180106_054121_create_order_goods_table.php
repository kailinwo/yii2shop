<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_goods`.
 */
class m180106_054121_create_order_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order_goods', [
            'id' => $this->primaryKey()->comment('主键id'),
            'order_id'=>$this->integer()->comment('订单id'),
            'goods_id'=>$this->integer()->comment('商品id'),
            'goods_name'=>$this->string()->comment('商品名称'),
            'logo'=>$this->string()->comment('商品logo图片'),
            'price'=>$this->decimal(10,2)->comment('商品价格'),
            'amount'=>$this->integer()->comment('商品数量'),
            'total'=>$this->decimal(10,2)->comment('小计'),
            'member_id'=>$this->integer()->comment('用户的id'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order_goods');
    }
}
