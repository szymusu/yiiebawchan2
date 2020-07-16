<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Profile */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Profiles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="card m-3" style="width: 100%;">
    <img src="https://i.ytimg.com/vi/muurR5-Je1o/hqdefault.jpg" class="card-img-top" alt="test żeber">
    <div class="card-body">
        <h5 class="card-title mb-5"><?= $model->name ?></h5>
        <p class="card-text"><?= Yii::$app->formatter->asParagraphs($model->description) ?></p>
    </div>
</div>
