<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 17/12/20
 * Time: 23:22
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'article_category_id')->dropDownList($categorys);
echo $form->field($model1,'content')->widget(\common\widgets\ueditor\Ueditor::className(),[
    'options'=>[
    'initialFrameWidth'=>850,
        ]
]);
echo $form->field($model,'sort')->textInput();
echo $form->field($model,'status',['inline'=>1])->radioList(['1'=>'正常','0'=>'隐藏']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();