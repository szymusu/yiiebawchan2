<?php

/**
 * @var $file File
 */

use common\models\File;

?>

<div class="embed-responsive embed-responsive-16by9">
    <video class="embed-responsive-item" src="<?= $file->getLink() ?>" controls></video>
</div>