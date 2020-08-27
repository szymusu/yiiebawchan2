<?php

use common\models\Group;
use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model Group */

$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

	<h1><?= Html::encode($this->title) ?></h1>

	<div>
        <div class="text-right mb-2">
        <?= Html::a('Leave Group', ['leave', 'link' => $model->link], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to leave this group?',
                'method' => 'post',
            ],
        ]) ?>
        </div>
        <div class="text-right">
		<?php
        if ($model->isModerator(Yii::$app->profile->getId()))
        {
            echo Html::a('Admin Panel', ['/group/panel', 'link' => $model->link], ['class' => 'btn btn-primary']);
        }
        ?>
        </div>
        <div>
		<?= Html::a('Create Post', ['/post/create', 'group' => $model->link], ['class' => 'btn btn-success']) ?>
        </div>
	</div>

	<?= ListView::widget([
		'dataProvider' => $dataProvider,
		'itemOptions' => ['tag' => false],
		'layout' => '<div class="container">{items}</div>{pager}',
		'itemView' => '@frontend/views/post/_post_item',
	]) ?>

</div>
