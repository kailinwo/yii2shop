<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 17/12/24
 * Time: 8:51
 * @var $this \yii\web\View
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'sn')->textInput();
echo $form->field($model,'logo')->hiddenInput();
//echo $form->field($model,'logo')->fileInput();
echo "<img src='".$model->logo."' width='200px' class='img-responsive' id='img'>";
//+++++++++++++++使用webuploader+++++++++++++++
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
$upload_url = \yii\helpers\Url::to(['goods/uploader']);
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
        mimeTypes: 'image/*'
    }
});
// 文件上传成功，给item添加成功class, 用样式标记上传成功。
uploader.on( 'uploadSuccess', function( file,response ) {
    //给图片回显
    $('#img').attr('src',response.url);
    //路径给logo字段
    $('#goods-logo').val(response.url);
});
JS;
$this->registerJs($js);
//+++++++++++++++使用webuploader+++++++++++++++
echo $form->field($model,'goods_category_id')->hiddenInput();
//==============使用zTree插件=======================
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);
//html结构
echo <<<HTML
<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>
HTML;
$nodes=\backend\models\Goods::getNodes();
if(!$model->id){//如果没有这个id传输过来就默认选择"顶级分类"
    $model->goods_category_id=0;
}
$js= <<<JS
        var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            },
            callback: {//给上面的隐藏域传入数据写入数据库
                onClick: function(event, treeId, treeNode){
                    $('#goods-goods_category_id').val(treeNode.id)
                }
            }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = {$nodes};
        zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        //展开全部的节点
        zTreeObj.expandAll(true);
        //默认选中或者回显
        var node = zTreeObj.getNodeByParam("id",$model->goods_category_id, null);
        zTreeObj.selectNode(node);
JS;
$this->registerJs($js);
//==============使用zTree插件=======================
echo $form->field($model,'brand_id')->dropDownList($brandsCategory);
echo $form->field($model,'market_price')->textInput();
echo $form->field($model,'shop_price')->textInput();
echo $form->field($model,'stock')->textInput();
echo $form->field($model,'sort')->textInput();
echo $form->field($goodsintro,'content')->widget(\common\widgets\ueditor\Ueditor::className(),['options'=>['initialFrameWidth'=>850,]]);
echo $form->field($model,'is_on_sale',['inline'=>1])->radioList([1=>'在售',0=>'下架']);
echo $form->field($model,'status',['inline'=>1])->radioList([1=>'正常',0=>'回收站']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();