<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = '编辑: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '会员信息', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-update">

    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>