<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 17/12/20
 * Time: 15:16
 * @var $this \yii\web\View  //定义好这个之后就可以支持ide的提示
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'logo')->hiddenInput();
//echo $form->field($model,'img')->fileInput()->label('图片');
echo "<img id='img' width='200px' src='".$model->logo."'/>";
//=======webuploader=========
//加载webuploader的css和js资源
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);
echo <<<HTML
<!--准备添加图片按钮的HTML结构-->
<div id="uploader-demo">
    <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <div id="filePicker">选择图片</div>
</div>

HTML;
//准备上传的路径>>ajax
$upload_url = \yii\helpers\Url::to(['brands/uploader']);
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
    $('#img').attr('src',response.url);
    //路径给logo字段
    $('#brands-logo').val(response.url);
});
JS;
$this->registerJs($js);
//=======webuploader=========
echo $form->field($model,'sort')->textInput();
echo $form->field($model,'status',['inline'=>1])->radioList([1=>'正常',0=>'隐藏']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
