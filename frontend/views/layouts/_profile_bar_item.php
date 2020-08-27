<?php

/**
 * @var $model Profile
 * @var $currentGroup Group
 */

use common\models\Group;
use common\models\Profile;
use yii\bootstrap4\Html;

$memberType = '';
if ($member = $model->getMemberOf($currentGroup))
{
    $memberType = sprintf('<div class="text-muted">%s</div>', $member->typeName());
}
?>

<li class="nav-item">
	<?= Html::a(
	    sprintf('<div>%s</div>%s', $model->name, $memberType),
		['/profile/switch/', 'link' => $model->link], ['class' => 'nav-link', 'data-method' => 'post'])
    ?>
</li>