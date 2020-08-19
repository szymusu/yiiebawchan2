<?php

/**
 * @var $file File
 */

use common\models\File;

?>

<img src="<?= $file->getLink() ?>" alt="image included in post" style="max-width: 100%">