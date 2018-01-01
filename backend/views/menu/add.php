<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 17/12/29
 * Time: 11:33
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'parent_id')->dropDownList($parent);
echo $form->field($model,'url')->dropDownList($permission);
echo $form->field($model,'sort')->textInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();