<?php

use yii\helpers\Html;
use yii\web\YiiAsset;

/* @var $this yii\web\View */
/* @var $model common\models\Profile */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Profiles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="row">
    <div class="col">
    <?= Html::a('Use this profile', ['switch', 'link' => $model->link], [
        'class' => 'btn btn-primary',
        'data' => [
            'method' => 'post',
        ],
    ]) ?>
    </div>
    <div class="col text-right">
    <?= Html::a('Edit', ['update', 'link' => $model->link], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Delete', ['delete', 'link' => $model->link], [
        'class' => 'btn btn-danger ml-2',
        'data' => [
            'confirm' => 'Are you sure you want to delete this item?',
            'method' => 'post',
        ],
    ])?>
    </div>
</div>
<div class="card m-3" style="width: 100%;">
    <img src="https://i.ytimg.com/vi/muurR5-Je1o/hqdefault.jpg" class="card-img-top" alt="test żeber">
    <div class="card-body">
        <h5 class="card-title mb-5 text-center"><?= $model->name ?></h5>
        <p class="card-text"><?= Yii::$app->formatter->asParagraphs($model->description) ?></p>
    </div>
</div>

