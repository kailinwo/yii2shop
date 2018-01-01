<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m171229_024024_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey()->comment('主键id'),
            'name'=>$this->string()->comment('名称'),
            'parent_id'=>$this->integer()->comment('上级菜单的id'),
            'url'=>$this->string()->comment('地址/路由'),
            'sort'=>$this->integer()->comment('排序'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
