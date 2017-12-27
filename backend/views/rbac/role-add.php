<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 17/12/27
 * Time: 14:05
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'description')->textInput();
echo $form->field($model,'permission',['inline'=>1])->checkboxList($permission);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();