<table class="table table-responsive">
    <tr>
        <th>菜单名称</th>
        <th>上级菜单</th>
        <th>地址/路由</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach($model as $row):?>
        <tr id="<?=$row->id?>">
            <td><?=$row->name?></td>
            <td><?=$row->parent_id?></td>
            <td><?=$row->url?></td>
            <td><?=$row->sort?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['menu/update','id'=>$row->id])?>" class="btn btn-warning">修改</a>
                <a class="btn btn-danger delete">删除</a>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
/**
 * @var $this \yii\web\View
 */
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
    'nextPageLabel'=>'下一页',
    'prevPageLabel'=>'上一页',
]);
$url = \yii\helpers\Url::to(['menu/delete']);
$js=<<<JS

$('.table').on('click','.delete',function(){
    var tr = $(this).closest('tr');
    if(confirm('您确定删除吗?该操作不可恢复!')){
        $.get("$url",{id:tr.attr('id')},function() {
          tr.fadeOut();
        })
    }
})
JS;
$this->registerJs($js);

