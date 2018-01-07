<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cart`.
 */
class m180105_034442_create_cart_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('cart', [
            'id' => $this->primaryKey()->comment('主键id'),
            'goods_id'=>$this->integer()->comment('商品id'),
            'amount'=>$this->integer()->comment('购买数量'),
            'member_id'=>$this->integer()->comment('用户id'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('cart');
    }
}
