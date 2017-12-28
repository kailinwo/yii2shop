
<?php
/**
 * @var $this \yii\web\View
 */
$this->registerCssFile('@web/DataTables/media/css/jquery.dataTables.css');
$this->registerJsFile('@web/DataTables/media/js/jquery.dataTables.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);
$url = \yii\helpers\Url::to(['rbac/permission-delete']);
$js=<<<JS
   $(document).ready(function() {
           $('#example').DataTable();
      });
    $('#example').dataTable({
        "oLanguage": {
            "sLengthMenu": "每页显示 _MENU_ 条记录",
            "sZeroRecords": "对不起，查询不到任何相关数据",
            "sInfo": "当前显示 _START_ 到 _END_ 条，共 _TOTAL_条记录",
            "sInfoEmtpy": "找不到相关数据",
            "sInfoFiltered": "数据表中共为 _MAX_ 条记录)",
            "sProcessing": "正在加载中...",
            "sSearch": "搜索",
            "oPaginate": {
            "sFirst": "第一页",
            "sPrevious":" 上一页 ",
            "sNext": " 下一页 ",
            "sLast": " 最后一页 "
            },
           }
        });
    $('#example').on('click','#delete',function(){
        var tr = $(this).closest('tr');
        if(confirm('您确定删除吗?该操作不可恢复!')){
            $.get("$url",{name:tr.attr('name')},function(){
                tr.fadeOut();
            })
        }
    })
JS;
$this->registerJs($js);

?>
<a href="<?=\yii\helpers\Url::to(['rbac/permission-add'])?>" class="btn btn-info">添加权限</a>
<table id="example" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>名称</th>
            <th>描述</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($model as $row):?>
        <tr name="<?=$row->name?>">
            <td><?=$row->name?></td>
            <td><?=$row->description?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['rbac/permission-update','name'=>$row->name])?>" class="btn btn-warning">修改</a>
                <a href="" class="btn btn-danger" id="delete">删除</a>
            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>

