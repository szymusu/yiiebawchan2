<?php
/**
 * @var $model Comment
 * @var $this \yii\web\View
 */

use common\models\Comment;
use yii\bootstrap4\Html;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;
use yii\widgets\Pjax;

?>

<div class="container mt-4 border-bottom <?= $model->is_reply ? 'ml-5' : '' ?>">
	<div class="col mb-2">
		<strong><?= Html::a($model->profile->name, '/profile/view/' . $model->profile->link) ?></strong><br/>
		<span class="text-muted">
            <?= Yii::$app->formatter->asRelativeTime($model->created_at) . ' • '
            . Yii::$app->formatter->asDate($model->created_at, 'medium') . ', '
            . Yii::$app->formatter->asTime($model->created_at, 'short')
            ?>
		</span>
	</div>
    <?= Yii::$app->formatter->asParagraphs($model->content) ?>
    <?php Pjax::begin() ?>
    <?= Html::a('Reply', ['get-reply-form', 'id' => $model->original_comment_id], [
        'class' => 'btn btn-primary',
        'data' => ['method' => 'get', 'pjax' => '1'],
    ]); ?>
    <?php Pjax::end() ?>
</div>
<div class="ml-3">
    <?php
    if (!($model->is_reply))
    {
        Pjax::begin();
        $dataProvider = new ActiveDataProvider([
            'query' => Comment::find()->replyTo($model)->latest(),
        ]);
        echo ListView::widget([
            'dataProvider' => $dataProvider,
            'itemOptions' => ['tag' => false],
            'layout' => '<div class="container comment-container">{items}</div>',
            'itemView' => '_comment_item',
            'emptyText' => false,
        ]);
        Pjax::end();
    }
    ?>
</div>
