<?php

use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model common\models\Group */
/* @var $dataProvider ActiveDataProvider */

if (empty($profile)) $profileId = Yii::$app->profile->getId();
else $profileId = $profile->getId();

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if ($model->hasJoinRequest($profileId))
        {
            echo Html::a('Cancel join request',
                ['join', 'link' => $model->link, 'cancelRequest' => true, 'profileId' => $profileId],
                ['class' => 'btn btn-secondary',
                'data' => ['method' => 'post']]
            );
        }
        else if ($model->isBanned($profileId))
        {
	        echo Html::a('You are banned from this group', ['#'], ['class' => 'btn btn-secondary']);
        }
        else
        {
	        echo Html::a('Join group', ['join', 'link' => $model->link, 'profileId' => $profileId], [
		        'class' => 'btn btn-primary',
		        'data' => ['method' => 'post']
	        ]);
        }
        ?>
    </p>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['tag' => false],
        'layout' => '<div class="container mt-5">{items}</div>{pager}',
        'itemView' => '@frontend/views/profile/_profile_item_small',
        'viewParams' => [
            'groupLink' => $model->link,
        ]
    ])
    ?>

</div>
