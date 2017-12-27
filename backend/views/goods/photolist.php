<?php
/**
 * @var $this \yii\web\View
 */
//加载webuploader的css和js资源
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);
//准备上传的路径>>ajax
$upload_url = \yii\helpers\Url::to(['goods/photoadd','id'=>$id]);
//准备删除的路径>>
$url=\yii\helpers\Url::to(['goods/photodelete']);
$js =  <<<JS
    var uploader = WebUploader.create({
    // 选完文件后，是否自动上传。
    auto: true,
    // swf文件路径backend/web/webuploader/Uploader.swf
    swf:'/webuploader/Uploader.swf', //更改为项目中的文件路径

    // 文件接收服务端。
    server: '{$upload_url}',//更换为项目中的文件路径

    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: '#filePicker',

    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,bmp,png',
        mimeTypes: 'image/gif,image/jpeg,image/png,image/jpg,image/bmp'
    }
});
// 文件上传成功，给item添加成功class, 用样式标记上传成功。
uploader.on( 'uploadSuccess', function( file,response ) {
    //给图片回显
    // $('#img').attr('src',response.url);
    //路径给logo字段
    // $('#brands-logo').val(response.url);
    var html = '<tr><td><img src="'+response.url+'" width="300px"></td><td><a class="btn btn-danger delete" >删除</a></td></tr>';
    $("#table").last().append(html);
});
    //删除的ajax操作
    $('#table').on('click','.delete',function(){
        var tr=$(this).closest('tr');
        $.get("$url",{id:tr.attr('id')},function(){
            tr.fadeOut();
            // alert(1111);
        })
    })
JS;
$this->registerJs($js);

?>
<!--准备添加图片按钮的HTML结构-->
<div id="uploader-demo">
    <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <div id="filePicker">选择图片</div>
</div>
<table id="table" style="width: 80%">
    <?php foreach($model as $row):?>
        <tr id="<?=$row->id?>" style="overflow: hidden">
            <td><img src="<?=$row->path?>" alt="" width="400px"></td>
            <td style="float: right"><a class="btn btn-danger delete" >删除</a></td>
        </tr>
    <?php endforeach;?>
</table>
