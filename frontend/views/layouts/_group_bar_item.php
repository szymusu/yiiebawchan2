<?php

/**
 * @var $model GroupMember
 */

use common\models\Group;
use common\models\GroupMember;

$group = Group::findById($model->group_id);

?>

<li class="nav-item">
	<a class="nav-link" href="/group/view/<?= $group->link ?>">
		<div><?= $group->name ?></div>
		<div class="text-muted"><?= $model->typeName() ?></div>
	</a>
</li>