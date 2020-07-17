<?php
/**
 * @var $model Post
 * @var $isPostPage bool
 * @var $this View
 */

use common\models\Post;
use yii\helpers\Html;
use yii\web\View;

?>

<div class="container mb-5 mt-4 border border-dark p-4 rounded">
    <div class="mb-3 row">
        <div class="col">
            <strong><?= Html::a($model->profile->name, '/profile/view/' . $model->profile->link) ?></strong><br/>
            <span class="text-muted">
            <?= Yii::$app->formatter->asRelativeTime($model->created_at) . ' • '
            . Yii::$app->formatter->asDate($model->created_at, 'medium') . ', '
            . Yii::$app->formatter->asTime($model->created_at, 'short')
            ?>
        </span>
        </div>

        <div class="col text-right">
            <?php
            if (empty($isPostPage))
            {
	            echo Html::a('Open', ['view', 'id' => $model->post_id], [
	                'class' => 'btn btn-primary ml-1', 'data' => ['method' => 'get']
                ]);
            }
            else
            {
                $this->title = 'Post by ' . $model->profile->name;
            }
            if ($model->isMine())
            {
                echo Html::a('Edit', ['edit', 'id' => $model->post_id], [ 'class' => 'btn btn-primary ml-1', ]);
                echo Html::a('Delete', ['delete', 'id' => $model->post_id], [
                    'class' => 'ml-2 btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this post?',
                        'method' => 'post',
                    ],
                ]);
            }
            ?>
        </div>
    </div>
    <div><?= Yii::$app->formatter->asParagraphs($model->content) ?></div>
    <div class="mt-3">
	    <?php \yii\widgets\Pjax::begin() ?>
        <?php
        if (!empty($isPostPage))
        {
	        echo $this->render('_reaction_bar', ['model' => $model]);
        }
        else
	    {
		    echo $this->render('_reaction_bar', ['model' => $model]);
	    }
        ?>
	    <?php \yii\widgets\Pjax::end() ?>
    </div>
</div>