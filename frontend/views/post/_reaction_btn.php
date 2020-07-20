<?php
/**
 * @var $reaction array
 * @var $postId string
 */

echo \yii\helpers\Html::a($reaction['name'], ['react', 'id' => $postId, 'type' => $reaction['type']], [
	'class' => 'btn mr-2 ' . ($reaction['state'] ? 'btn-primary' : 'btn-secondary'),
	'data' => ['method' => 'post', 'pjax' => '1'],
]);