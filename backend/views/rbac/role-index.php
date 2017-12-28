<a href="<?=\yii\helpers\Url::to(['rbac/role-add'])?>" class="btn btn-primary">添加角色</a>
<table class="table table-bordered text-center" id="table">
    <tr>
            <td>角色名称</td>
            <td>角色描述</td>
            <td>操作</td>
    </tr>
    <?php foreach($model as $row):?>
    <tr name="<?=$row->name?>">
        <td><?=$row->name?></td>
        <td><?=$row->description?></td>
        <td>
            <a href="<?=\yii\helpers\Url::to(['rbac/role-update','name'=>$row->name])?>" class="btn btn-warning">修改</a>
            <a class="btn btn-danger" id="delete">删除</a>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<?php
/**
 * @var $this \yii\web\View
 */
$url= \yii\helpers\Url::to(['rbac/role-delete']);
$js=<<<JS
$('#table').on('click','#delete',function(){
    var tr = $(this).closest('tr');
    if(confirm('您确定删除吗?该操作不可恢复!')){
        $.get("$url",{name:tr.attr('name')},function(){
            // alert(111);
            tr.fadeOut(); 
        });
    }
})
JS;
$this->registerJs($js);

