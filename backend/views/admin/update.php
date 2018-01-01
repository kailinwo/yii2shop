<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 17/12/24
 * Time: 23:53
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'email')->textInput();
echo $form->field($model,'status',['inline'=>1])->radioList([1=>'正常',0=>'禁用']);
echo $form->field($model,'role')->checkboxList($role);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();