<table class="table text-center" id="table">
    <tr>
        <th class="text-center">序号</th>
        <th class="text-center">用户名</th>
        <th class="text-center">密码</th>
        <th class="text-center">邮箱</th>
        <th class="text-center">状态</th>
        <th class="text-center">操作</th>
    </tr>
    <?php foreach($model as $row):?>
        <tr id="<?=$row->id?>">
            <td><?=$row->id?></td>
            <td><?=$row->username?></td>
            <td><?=substr($row->password_hash,0,4).'...'?></td>
            <td><?=$row->email?></td>
            <td><?=$row->status==1?'正常':'禁用'?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['admin/update','id'=>$row->id])?>" class="btn btn-warning">修改</a>
                <a class="btn btn-danger" id="delete">删除</a>
            </td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="6">
            <a href="<?=\yii\helpers\Url::to(['admin/add'])?>" class="btn btn-info">添加</a>
        </td>
    </tr>
</table>
<?php
/**
 * @var $this \yii\web\View
 */
$url = \yii\helpers\Url::to(['admin/delete']);
$js=<<<JS
$('#table').on('click','#delete',function(){
    var tr = $(this).closest('tr');
        if(confirm('您确定删除吗?该操作不可恢复!')){
            $.get("$url",{id:tr.attr('id')},function(){
            tr.fadeOut();
        })
    }
})
JS;
$this->registerJs($js);
