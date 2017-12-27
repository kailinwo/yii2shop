<form class="form-inline">
    <div class="form-group">
        <label for="exampleInputName2">商品名:</label>
        <input type="text" class="form-control" name='name' placeholder="请输入商品名搜索">
    </div>
    <div class="form-group">
        <label for="exampleInputEmail2">货号:</label>
        <input type="email" class="form-control" name="sn" id="exampleInputEmail2" placeholder="请输入货号搜索">
    </div>
    <button type="submit" class="btn btn-info">搜索</button>
</form>
<div>
    &emsp;
</div>



<table class="table table-bordered text-center" id="table">
    <tr >
        <th class="text-center">序号</th>
        <th class="text-center">名称</th>
        <th class="text-center">货号</th>
        <th class="text-center">logo</th>
        <th class="text-center">分类</th>
        <th class="text-center">品牌</th>
        <th class="text-center">市场价</th>
        <th class="text-center">售价</th>
        <th class="text-center">库存</th>
        <th class="text-center">是否在售</th>
        <th class="text-center">商品状态</th>
        <th class="text-center">排序</th>
        <th class="text-center">添加时间</th>
        <th class="text-center">浏览次数</th>
        <th class="col-xs-2 text-center">操作</th>
    </tr>
    <?PHP foreach($model as $row):?>
        <tr id="<?=$row->id?>">
            <td><?=$row->id?></td>
            <td><?=$row->name?></td>
            <td><?=$row->sn?></td>
            <td><img src="<?=$row->logo?>" alt="" width="100px"></td>
            <td><?=$row->goods_category_id?></td>
            <td><?=$row->brand_id?></td>
            <td><?=$row->market_price?></td>
            <td><?=$row->shop_price?></td>
            <td><?=$row->stock?></td>
            <td><?=$row->is_on_sale==1?'在售':'下架'?></td>
            <td><?=$row->status==1?'正常':'回收站'?></td>
            <td><?=$row->sort?></td>
            <td><?=date('Y/m/d H:i',$row->create_time)?></td>
            <td><?=$row->view_times?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['goods/photo','id'=>$row->id])?>" class="btn btn-info">图册</a>
                <a href="<?=\yii\helpers\Url::to(['goods/edit','id'=>$row->id])?>" class="btn btn-warning">修改</a>
                <a href="" class="btn btn-danger" id="delete">删除</a>
            </td>
        <tr>
    <?PHP endforeach;?>
    <tr>
        <td colspan="15">
            <a href="<?=\yii\helpers\Url::to(['goods/add'])?>" class="btn btn-info">添加</a>
        </td>
    </tr>
</table>



<?php
/**
 * @var $this \yii\web\View
 */
//做分页工具条
echo \yii\widgets\LinkPager::widget([
        'pagination'=>$pager,
        'nextPageLabel'=>'上一页',
        'prevPageLabel'=>'下一页',
]);
$url=\yii\helpers\Url::to(['goods/delete']);
$js =<<<JS
$('#table').on('click','#delete',function(){
    var tr = $(this).closest('tr');
    $.get("$url",{id:tr.attr('id')},function(){
          tr.fadeOut();
          // alert(111);
    })
})
JS;
$this->registerJs($js);//注册js


