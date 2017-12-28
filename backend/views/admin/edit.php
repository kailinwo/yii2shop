<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 17/12/28
 * Time: 16:20
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'old_password_hash')->passwordInput();
echo $form->field($model,'password_hash')->passwordInput();
echo $form->field($model,'re_password_hash')->passwordInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();