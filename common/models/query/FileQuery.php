<?php

namespace common\models\query;

use common\models\File;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\common\models\File]].
 *
 * @see \common\models\File
 */
class FileQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return File[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return File|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
