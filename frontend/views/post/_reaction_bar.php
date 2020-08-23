<?php
/**
 * @var $model Post
 * @var $this View
 * @var $reactions array<string, bool>
 * @var $access bool
 */

use common\models\Post;
use common\models\Reaction;
use yii\web\View;

$canIAccess = $model->canIAccess();

if ($canIAccess)
{
	$reactions = $model->getMyReactions();
	$postId = $model->post_id;
}
else
{
	$reactions = Reaction::$TYPE;
}

foreach ($reactions as $name => $state)
{
	if ($canIAccess)
	{
		echo $this->render('_reaction_btn', [
			'reactionName' => $name, 'reactionState' => $state, 'postId' => $postId, 'count' => $model->getReactionCount($name)
		]);
	}
	else
	{
		echo $this->render('_reaction_show', [
			'reactionName' => $name, 'count' => $model->getReactionCount($name)
		]);
	}
}