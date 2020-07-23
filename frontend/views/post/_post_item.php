<?php
/**
 * @var $model Post
 * @var $isPostPage bool
 * @var $this View
 * @var $dataProvider ActiveDataProvider
 */

use common\models\Comment;
use common\models\Post;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ListView;
use yii\widgets\Pjax;

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
	    <?php Pjax::begin(['scrollTo' => false]) ?>
        <?= $this->render('_reaction_bar', ['model' => $model]); ?>
	    <?php Pjax::end() ?>
    </div>

    <div class="mb-5 mt-5 p-0 pb-1 border border-dark rounded"></div>

    <div class="container">
        <?php Pjax::begin() ?>
        <?= $this->render('_comment_form', ['model' => $model]) ?>
        <?php Pjax::end() ?>
    </div>
    <div class="container">
	    <?php Pjax::begin(); ?>
        <?php $dataProvider = new ActiveDataProvider([
		    'query' => Comment::find()->onPost($model)->reply(false)->latest(),
        ]) ?>
	    <?= ListView::widget([
		    'dataProvider' => $dataProvider,
		    'itemOptions' => ['tag' => false],
		    'layout' => '<div class="container">{items}</div>',
		    'itemView' => '_comment_item',
		    'emptyText' => false,
	    ]) ?>

	    <?php Pjax::end(); ?>
    </div>
</div>