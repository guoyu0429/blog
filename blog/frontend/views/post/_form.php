<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PostsModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="posts-model-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    
    <?=$form->field($model,'cat_id')->textInput() ?>

     <?= $form->field($model, 'label_img')->widget('common\widgets\file_upload\FileUpload',[
        'config'=>[
            //图片上传的一些配置，不写调用默认配置
            'domain_url' => 'http://www.yii-china.com',
        ]
    ]) ?>
    
    <?= $form->field($model, 'content')->widget('common\widgets\ueditor\Ueditor',[
    'options'=>[
        'initialFrameWidth' => 1150,
        'initialFrameHeight' => 400,
       // 'toolbars'=>[]  可以需要哪些加哪些
    ]
]) ?>
    


    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

