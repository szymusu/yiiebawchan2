<?php
/**
 * @var $model \common\models\Post
 * @var $this \yii\web\View
 * @var $reactions array
 */

use yii\helpers\Url;


$reactions = $model->getMyReactions();
$postId = $model->post_id;
?>

<div><?= $model->getReactionCount() ?> total reactions</div>

<?php foreach ($reactions as $reaction)
{
    echo $this->render('_reaction_btn', ['reaction' => $reaction, 'postId' => $postId]);
}?>