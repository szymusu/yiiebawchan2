<?php

use common\models\Group;
use common\models\Profile;
use yii\bootstrap4\Html;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\widgets\ListView;

$currentProfile = Yii::$app->profile->get();
$currentGroup = Group::getCurrent();
?>

<aside class="shadow" style="right: 0; position: fixed; max-width: 19%">
    <div class="text-right">
        <h4 class="m-3">Profiles</h4>
    </div>
    <div class="m-1 text-right">
		<?= Html::a('Your profiles...', ['/profile/'], ['class' => 'text-muted']) ?>
    </div>
    <div class="m-2">
		<?php if ($currentProfile)
		{
		    echo "<h6>$currentProfile->name</h6>";
			if ($currentGroupMember = $currentProfile->getMemberOf($currentGroup))
			{
				echo sprintf('<span class="text-muted mt-1">%s</span>', $currentGroupMember->typeName());
			}
		}
		?>
    </div>
	<?php
    if ($currentGroup)
    {
        $subQuery = new Query();
        $subQuery->select(['type', 'profile_id'])
            ->from('group_member')
            ->andWhere(['group_id' => $currentGroup->group_id]);

        $dataProvider = new ActiveDataProvider([
            'query' => Profile::find()
                ->leftJoin(['member' => $subQuery], 'profile.profile_id = member.profile_id')
                ->onlyMine()
                ->andWhere($currentProfile ? sprintf("profile.profile_id != '%s'", $currentProfile->profile_id) : [])
                ->orderBy(['member.type' => SORT_DESC, 'last_login' => SORT_DESC])
        ]);
    }
    else
    {
	    $dataProvider = new ActiveDataProvider([
		    'query' => Profile::find()
                ->onlyMine()
			    ->andWhere($currentProfile ? sprintf("profile.profile_id != '%s'", $currentProfile->profile_id) : [])
                ->orderBy(['last_login' => SORT_DESC])
        ]);
    }
	?>

	<?= ListView::widget([
		'dataProvider' => $dataProvider,
		'itemOptions' => ['tag' => false],
		'layout' => '<div class="container border-top">{items}</div>',
		'itemView' => '_profile_bar_item',
		'emptyText' => false,
        'viewParams' => [
            'currentGroup' => $currentGroup,
        ],
	]) ?>
</aside>