<?php
/**
 * @var $model Profile
 * @var $groupLink string
 */

use common\models\Profile;
use yii\helpers\Html;

?>
<div class="row" style="width: 30rem; max-height: 6px">
	<div class="col">
		<img src="https://i.ytimg.com/vi/muurR5-Je1o/hqdefault.jpg" class="card-img-top" alt="test żeber">
		<h5><?= $model->name ?></h5>
	</div>
	<div class="col">
		<p class="text-muted card-text"><?= '/' . $model->link ?></p>
		<?= Html::a('Use this profile', ['/profile/switch', 'link' => $model->link, 'group' => $groupLink], [
			'class' => 'btn btn-primary',
			'data' => [
				'method' => 'post',
			],
		]) ?>
	</div>
</div>
