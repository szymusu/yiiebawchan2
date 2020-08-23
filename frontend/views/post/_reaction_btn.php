<?php
/**
 * @var $reactionName string
 * @var $reactionState bool
 * @var $postId string
 * @var $count int
 */

use common\models\Reaction;
use yii\bootstrap4\Html;

echo Html::a(sprintf('<i class="%s reaction-icon %s">
					<span style="font-size: 12px; font-family: Arial,sans-serif; margin-left: 7px;">%s</span>
				  </i>', $reactionName, ($reactionState ? 'colored' : ''), $count),
	['/post/react', 'id' => $postId, 'type' => Reaction::getTypeNumber($reactionName)], [
		'class' => 'btn mr-2 btn-outline-secondary',
		'data' => ['method' => 'post', 'pjax' => '1'],
]);