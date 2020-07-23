<?php

/* @var $this yii\web\View */

use common\models\Comment;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $model common\models\Post */
/* @var $form yii\bootstrap4\ActiveForm */

?>

<div class="post-form">

    <?php $form = ActiveForm::begin(['action' => ['post/comment', 'id' => $model->post_id], 'options' => [
	    'class' => 'form-post-comment'
    ]]); ?>

    <?= $form->field(new Comment(), 'content')->textarea(['rows' => 6,
            'data-pjax' => '1',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>