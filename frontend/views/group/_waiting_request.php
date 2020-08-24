<?php
/**
 * @var $model GroupMember
 */

use common\models\GroupMember;
use common\models\MemberAcceptForm;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$profile = $model->profile;
$formModel = new MemberAcceptForm();

?>
<div class="row d-flex">
	<div class="col">
		<img src="https://i.ytimg.com/vi/muurR5-Je1o/hqdefault.jpg" alt="test żeber" style="max-height: 70px">
	</div>
    <div class="col">
		<h5><?= $profile->name ?></h5>
    </div>
	<div class="col">
		<?php $form = ActiveForm::begin([
			'id' => 'member-accept-form',
			'action' => ['/group/accept-member', 'link' => $model->group->link]
		]) ?>

		<?= $form->field($formModel, 'profile_id', ['template' => "{input}"])
			->hiddenInput(['value' => $profile->profile_id]) ?>
		<?= $form->field($formModel, 'type', ['template' => "{input}"])
			->hiddenInput(['value' => true]) ?>

		<?= Html::submitButton('Accept', [
			'class' => 'btn btn-success',
		]) ?>

		<?php ActiveForm::end() ?>
	</div>
	<div class="col">
		<?php $form = ActiveForm::begin([
			'id' => 'member-accept-form',
			'action' => ['/group/accept-member', 'link' => $model->group->link]
		]) ?>

		<?= $form->field($formModel, 'profile_id', ['template' => "{input}"])
			->hiddenInput(['value' => $profile->profile_id]) ?>
		<?= $form->field($formModel, 'type', ['template' => "{input}"])
			->hiddenInput(['value' => false]) ?>

		<?= Html::submitButton('Reject', [
			'class' => 'btn btn-danger',
		]) ?>

		<?php ActiveForm::end() ?>
	</div>
</div>
