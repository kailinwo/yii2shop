
<table class="table table-bordered" style="text-align: center">
    <tr>
        <th style="text-align: center">序号</th>
        <th style="text-align: center">名称</th>
        <th style="text-align: center">简介</th>
        <th style="text-align: center">分类</th>
        <th style="text-align: center">排序</th>
        <th style="text-align: center">状态</th>
        <th style="text-align: center">创建时间</th>
        <th style="text-align: center">操作</th>
    </tr>
    <?php foreach($model as $row):?>
        <tr id="<?=$row->id?>">
            <td><?=$row->id?></td>
            <td><?=$row->name?></td>
            <td><?=$row->intro?></td>
            <td><?=$row->articleCategory?$row->articleCategory->name:''?></td>
            <td><?=$row->sort?></td>
            <td><?php if($row->status==1){echo"正常";}elseif($row->status==0){echo "隐藏";}else{echo "删除";}?></td>
            <td><?=date('Y/m/d H:i',$row->create_time)?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['article/update','id'=>$row->id])?>" class="btn btn-warning " >修改</a>
                <button  class="btn btn-danger delete" >删除</button>
            </td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="8">
            <a href="<?=\yii\helpers\Url::to(['article/add'])?>" class="btn btn-info">添加</a>
        </td>
    </tr>
</table>
<?php
/**
 * @var $this \yii\web\View
 */
$url=\yii\helpers\Url::to(['article/delete']);
$js=<<<JS
    $('table').on('click','.delete',function(){
       var tr = $(this).closest('tr');
       if(confirm('您确定删除吗?该操作不可恢复!')){
           $.get("$url",{id:tr.attr('id')},function(){
               tr.fadeOut();
           })
       }
    })
JS;

$this->registerJs($js);
?>