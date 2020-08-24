<?php

/* @var $this View */
/* @var $content string */

use yii\web\View;

$this->beginContent('@frontend/views/layouts/base.php');
?>
    <main class="d-flex">
		<?php echo $this->render('_group_bar') ?>
		<?php echo $this->render('_profile_bar') ?>

        <div class="content-wrapper p-3">
			<?= $content ?>
        </div>
    </main>
<?php $this->endContent() ?>