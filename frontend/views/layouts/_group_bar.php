<?php

use common\models\Group;
use common\models\GroupMember;
use yii\bootstrap4\Html;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;

$currentGroup = Group::getCurrent();
?>

<aside class="shadow" style="left: 0; position: fixed; max-width: 19%">
    <div>
        <h4 class="m-3">Groups</h4>
    </div>
    <div class="m-1 text-right">
        <?= Html::a('Find new groups...', ['/group/'], ['class' => 'text-muted']) ?>
    </div>
    <div class="m-2">
        <?php if ($currentGroup)
        {
            if ($currentGroupMember = $currentGroup->getMyMember())
            {
                echo "<h6>$currentGroup->name</h6>";
                echo sprintf("<span class='text-muted mt-1'>%s</span>", $currentGroupMember->typeName());
            }
        }
        ?>
    </div>
	<?php
	$dataProvider = new ActiveDataProvider([
		'query' => GroupMember::find()
			->andWhere(['profile_id' => Yii::$app->profile->getId()])
			->andWhere($currentGroup ? sprintf("group_id != '%s'", $currentGroup->group_id) : [])
			->orderBy(['type' => SORT_DESC]),
	]);
	?>

	<?= ListView::widget([
		'dataProvider' => $dataProvider,
		'itemOptions' => ['tag' => false],
		'layout' => '<div class="container border-top">{items}</div>',
		'itemView' => '_group_bar_item',
		'emptyText' => false,
	]) ?>
</aside>