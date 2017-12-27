<table class="table text-center table-bordered" id="table">
    <tr>
        <th style="text-align: center">序号</th>
        <th style="text-align: center">名称</th>
        <th style="text-align: center">上级分类</th>
        <th style="text-align: center">简介</th>
        <th style="text-align: center">操作</th>
    </tr>
    <?php foreach($model as $row):?>
        <tr id="<?=$row->id?>">
            <td><?=$row->id?></td>
            <td><?=$row->name?></td>
            <td><?=$row->parent_id?></td>
            <td><?=$row->intro?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['goods-category/update','id'=>$row->id])?>" class="btn btn-warning">修改</a>
                <a id="delete" class="btn btn-danger ">删除</a>
            </td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="5">
            <a href="<?=\yii\helpers\Url::to(['goods-category/add'])?>" class="btn btn-info">添加</a>
        </td>
    </tr>
</table>
<?php
/**
 * @var $this \yii\web\View
 */
echo \yii\widgets\LinkPager::widget([
        'pagination'=>$pager,
        'nextPageLabel'=>'下一页',
        'prevPageLabel'=>'上一页'
]);
$url=\yii\helpers\Url::to(['goods-category/delete']);
$js=<<<JS
    //使用事件委派来做ajax删除
    $('#table').on('click','#delete',function(){
        //向上查找得到tr
        var tr = $(this).closest('tr');
        if(confirm('您确定删除吗?该操作不可恢复!')){
            $.get("$url",{id:tr.attr('id')},function(date){
                if(date){
                    alert('该分类下面尚有子分类不可删除!')
                }else{
                    tr.fadeOut();
                }
            })
        }
    })
JS;
$this->registerJs($js);
?>
