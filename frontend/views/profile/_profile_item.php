<?php
/**
 * @var $model \common\models\Profile
 */

use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="card m-3" style="width: 16rem;">
    <img src="https://i.ytimg.com/vi/muurR5-Je1o/hqdefault.jpg" class="card-img-top" alt="test żeber">
    <div class="card-body">
        <h5 class="card-title"><?= $model->name ?></h5>
        <p class="text-muted card-text"><?= '/profile/view/' . $model->link ?></p>
        <?= Html::a('Use this profile', ['switch', 'link' => $model->link], [
            'class' => 'btn btn-primary',
            'data' => [
                'method' => 'post',
            ],
        ]) ?>
        <a href="<?= Url::to(['@web/profile/update/' . $model->link]) ?>" class="btn btn-primary">Edit</a>
    </div>
</div>
