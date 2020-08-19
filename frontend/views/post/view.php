<?php
/**
 * @var $model Post
 * @var $this View
 */

use common\models\Post;
use yii\web\View;

echo $this->render('_post_item', ['model' => $model, 'isPostPage' => true]);