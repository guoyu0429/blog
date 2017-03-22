<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->title='创建文章';
$this->params['breadcrumbs'][]=['label'=>'文章','url'=>['post/index']];
$this->params['breadcrums'][]=$this->title;
?>
<div class="row">
  <div class="col-lg-9">
    <div class="panel-title box-title">
       <span>创建文章</span>
    </div>
    <div class="panel-body">
      <?php $form=ActiveForm::begin()?>
      <?=$form->field($model,'title')->textinput(['maxlength'=>true])?>
      <?=$form->field($model,'cat_id')->dropDownList($cat)?>
    <?= $form->field($model, 'label_img')->widget('common\widgets\file_upload\FileUpload',[
        'config'=>[
            //图片上传的一些配置，不写调用默认配置
            'domain_url' => 'http://www.yii-china.com',
        ]
    ]) ?>

    

<?= $form->field($model, 'content')->widget('common\widgets\ueditor\Ueditor',[
    'options'=>[
        'initialFrameWidth' => 858,
        'initialFrameHeight' => 400,
       // 'toolbars'=>[]  可以需要哪些加哪些
    ]
]) ?>
      <?=$form->field($model,'tags')->widget('common\widgets\tags\TagWidget')?>
      <div class="form-group">
        <?=Html::submitButton("发布",['class'=>'btn btn-success'])?>
      </div>
      
    </div>
    <?php ActiveForm::end()?>
  </div>
  <div class="col-lg-3">
   <div class="panel-title box-title">
       <span>注意事项</span>
    </div>
    <div class="panel-body">
    <p></p>
    </div>
  </div>
</div>