<?php

use common\models\Group;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model Group */

$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

	<h1><?= Html::encode($this->title) ?></h1>

	<p>
		<?= Html::a('Create Post', ['/post/create', 'group' => $model->link], ['class' => 'btn btn-success']) ?>
	</p>

	<?php Pjax::begin(); ?>

	<?= ListView::widget([
		'dataProvider' => $dataProvider,
		'itemOptions' => ['tag' => false],
		'layout' => '<div class="container">{items}</div>{pager}',
		'itemView' => '@frontend/views/post/_post_item',
	]) ?>

	<?php Pjax::end(); ?>

</div>
