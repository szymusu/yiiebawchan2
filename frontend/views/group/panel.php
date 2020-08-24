<?php

use common\models\Group;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model Group */

$this->title = $model->name;

?>
<div class="post-index">

	<h1><?= Html::encode($this->title) ?></h1>

    <h2 class="mt-3">Waiting join requests</h2>
    <div>
    <?php Pjax::begin(); ?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['tag' => false],
        'layout' => '<div class="container">{items}</div>{pager}',
        'itemView' => '_waiting_request',
        'emptyText' => 'No requests found',
    ]) ?>

    <?php Pjax::end(); ?>
    </div>
</div>