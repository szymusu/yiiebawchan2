<?php

use yii\bootstrap4\Alert;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
if (Yii::$app->session->hasFlash('fileUploadError'))
{
	echo Alert::widget([
		'options' => ['class' => 'alert-danger'],
		'body' => Yii::$app->session->getFlash('fileUploadError'),
	]);
}
?>
<div class="post-form">

    <?php $form = ActiveForm::begin([
	    'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="upload" name="upload">
            <label class="custom-file-label" for="upload">Choose file</label>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
