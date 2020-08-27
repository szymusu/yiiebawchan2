<?php

use common\models\Group;
use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model Group */

$this->title = $model->name;

?>
<div class="post-index">

	<h1><?= Html::encode($this->title) ?></h1>

    <div class="text-right">
		<?php
		if ($model->isAdmin(Yii::$app->profile->getId()))
		{
			echo Html::a('Edit group', ['/group/update', 'link' => $model->link], ['class' => 'btn btn-primary']);
		}
		?>
    </div>

    <h2 class="mt-3">Waiting join requests</h2>
    <div>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['tag' => false],
        'layout' => '<div class="container">{items}</div>{pager}',
        'itemView' => '_waiting_request',
        'emptyText' => 'No requests found',
    ]) ?>
    </div>
</div>