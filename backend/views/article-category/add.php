<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 17/12/20
 * Time: 15:16
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput()->label('名称');
echo $form->field($model,'intro')->textarea()->label('简介');
echo $form->field($model,'sort')->textInput()->label('排序');
echo $form->field($model,'status',['inline'=>1])->radioList([1=>'正常',0=>'隐藏'])->label('状态');
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();