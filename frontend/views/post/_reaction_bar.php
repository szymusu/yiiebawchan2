<?php
/**
 * @var $model \common\models\Post
 * @var $this \yii\web\View
 * @var $reactions array<string, bool>
 */

$reactions = $model->getMyReactions();
$postId = $model->post_id;
?>

<div><?= $model->getReactionCount() ?> total reactions</div>

<?php foreach ($reactions as $name => $state)
{
    echo $this->render('_reaction_btn', ['reactionName' => $name, 'reactionState' => $state, 'postId' => $postId]);
}?>