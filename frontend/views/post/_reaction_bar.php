<?php
/**
 * @var $model \common\models\Post
 * @var $this \yii\web\View
 */

use yii\helpers\Url;

?>

<div><?= $model->getReactionCount() ?> total reactions</div>
<a href="<?= Url::to(['/post/react', 'id' => $model->post_id, 'type' => 1]) ?>"
   class="btn btn-primary ml-2" data-method="post" data-pjax="1">React</a>
