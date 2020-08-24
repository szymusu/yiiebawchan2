<?php

use common\models\GroupMember;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;

?>

<aside class="shadow" style="left: 0; position: fixed">
	<?php
	$dataProvider = new ActiveDataProvider([
		'query' => GroupMember::find()
			->andWhere(['profile_id' => Yii::$app->profile->getId()])
			->orderBy(['type' => SORT_DESC]),
	]);
	?>

	<?= ListView::widget([
		'dataProvider' => $dataProvider,
		'itemOptions' => ['tag' => false],
		'layout' => '<div class="container border-top">{items}</div>',
		'itemView' => '_group_bar_item',
		'emptyText' => false,
	]) ?>
</aside>