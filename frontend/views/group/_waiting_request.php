<?php
/**
 * @var $model \common\models\GroupMember
 */

use yii\helpers\Html;

$profile = $model->profile;

?>
<div class="row d-flex">
	<div class="col">
		<img src="https://i.ytimg.com/vi/muurR5-Je1o/hqdefault.jpg" alt="test żeber" style="max-height: 70px">
	</div>
    <div class="col">
		<h5><?= $profile->name ?></h5>
    </div>
	<div class="col">
		<?= Html::a('Accept', ['#'], [
			'class' => 'btn btn-success',
			'data' => [
				'method' => 'post',
			],
		]) ?>
	</div>
	<div class="col">
		<?= Html::a('Reject', ['#'], [
			'class' => 'btn btn-danger',
			'data' => [
				'method' => 'post',
			],
		]) ?>
	</div>
</div>
