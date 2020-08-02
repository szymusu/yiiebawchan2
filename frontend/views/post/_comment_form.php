<?php

/* @var $this yii\web\View */

use common\models\Comment;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $model common\models\Post */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $comment Comment */

if (empty($comment))
{
	$comment = new Comment();
}
?>

<div class="post-form border-top">

    <?php $form = ActiveForm::begin(['action' => ['/post/comment', 'id' => $model->post_id], 'options' => [
	    'class' => 'form-post-comment',
        'data-pjax' => '1',
        'onfocusin' => 'prepareForSend(this)' //TODO do it normally, this is total hack
    ]]); ?>

    <?= $form->field(new Comment(), 'content')->textarea(['rows' => 6,]) ?>
    <?= $form->field($comment, 'original_comment_id', ['template' => '{input}'])->textInput(['hidden' => 1]) ?>

    <div class="form-group text-right">
        <?= Html::submitButton('Comment', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>