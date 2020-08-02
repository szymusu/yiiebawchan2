<?php
/**
 * @var $reactionName string
 * @var $reactionState bool
 * @var $postId string
 */

use common\models\Reaction;
use yii\helpers\Html;

echo Html::a($reactionName, ['/post/react', 'id' => $postId, 'type' => Reaction::getTypeNumber($reactionName)], [
	'class' => 'btn mr-2 ' . ($reactionState ? 'btn-primary' : 'btn-secondary'),
	'data' => ['method' => 'post', 'pjax' => '1'],
]);