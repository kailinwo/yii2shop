<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 17/12/22
 * Time: 10:53
 * @var $this \yii\web\View
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'parent_id')->hiddenInput();
//===================zTree================================
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
echo <<<HTML
<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>
HTML;
$nodes=\backend\models\GoodsCategory::getNodes();
//如果有就回显.没有就不显示

//if(!$model->id){//如果没有这个id传输过来就默认选择"顶级分类"
//    $model->parent_id=0;
//}
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
            callback: {
                onClick: function(event, treeId, treeNode){
                    $('#goodscategory-parent_id').val(treeNode.id)
                }
            }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = {$nodes};
        zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        //展开全部的节点
        zTreeObj.expandAll(true);
        //默认选中或者回显
        var node = zTreeObj.getNodeByParam("id",'$model->parent_id', null);
        zTreeObj.selectNode(node);
JS;

$this->registerJs($js);
//========================================================
echo $form->field($model,'intro')->textInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();