<?php
/**
 * @var $model Post
 * @var $isPostPage bool
 * @var $this View
 * @var $dataProvider ActiveDataProvider
 */

use common\models\Comment;
use common\models\File;
use common\models\Post;
use common\models\query\CommentQuery;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ListView;
use yii\widgets\Pjax;

if (empty($isPostPage)) $isPostPage = false;

$file = File::findOnSource($model->post_id);
$canIAccess = $model->canIAccess();
?>

<div class="container mb-5 mt-4 border border-dark p-4 rounded post-item">
    <div class="mb-3 row">
        <div class="col">
            <strong><?= Html::a($model->profile->name, sprintf("/profile/view/%s", $model->profile->link)) ?>  ->  </strong>
            <strong><?= Html::a($model->groupName(), sprintf("/group/view/%s", $model->groupLink())) ?></strong><br/>
            <span class="text-muted">
            <?= sprintf("%s • %s, %s", Yii::$app->formatter->asRelativeTime($model->created_at),
                Yii::$app->formatter->asDate($model->created_at, 'medium'),
                Yii::$app->formatter->asTime($model->created_at, 'short'))
            ?>
            </span>
        </div>

        <div class="col text-right">
            <?php
            if (!$isPostPage)
            {
	            echo Html::a('Open', ['/post/view', 'id' => $model->post_id], [
	                'class' => 'btn btn-primary ml-1', 'data' => ['method' => 'get']
                ]);
            }
            else
            {
                $this->title = 'Post by ' . $model->profile->name;
            }
            if ($model->isMine())
            {
                echo Html::a('Edit', ['/post/edit', 'id' => $model->post_id], [ 'class' => 'btn btn-primary ml-1', ]);
                echo Html::a('Delete', ['/post/delete', 'id' => $model->post_id], [
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
    <div class="container" style="max-width: 100%">
        <?php
        if (!empty($file))
        {
            echo $this->render('_' . $file->typeName(), ['file' => $file, ]);
        }
        ?>
    </div>
    <div class="mt-3">
        <?php
        Pjax::begin();
        echo $this->render('_reaction_bar', ['model' => $model, 'access' => $canIAccess]);
        Pjax::end();
        ?>
    </div>

    <div class="mb-5 mt-5 p-0 pb-1 border border-dark rounded"></div>

    <div class="container">
	    <?php
	    if ($canIAccess)
	    {
		    Pjax::begin(['scrollTo' => false]);
		    echo $this->render('_comment_form', ['model' => $model]);
		    Pjax::end();
	    }
	    ?>
    </div>
    <div class="container">
	    <?php Pjax::begin(); ?>
        <?php
        /** @var CommentQuery $query */
	    $query = Comment::find()->onPost($model)->reply(false)->latest();
        $config = [];
        if (!$isPostPage)
        {
	        $query = $query->limit(5);
	        $config['pagination'] = false;
        }
        $config['query'] = $query;
        $dataProvider = new ActiveDataProvider($config)
        ?>
	    <?= ListView::widget([
		    'dataProvider' => $dataProvider,
		    'itemOptions' => ['tag' => false],
		    'layout' => '<div class="container comment-container">{items}</div>',
		    'itemView' => '_comment_item',
		    'emptyText' => false,
		    'viewParams' => [
			    'isPostPage' => $isPostPage,
			    'canIAccess' => $canIAccess,
		    ],
	    ]) ?>

	    <?php Pjax::end(); ?>
    </div>
</div>