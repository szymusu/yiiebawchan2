<?php

namespace common\models\query;

use common\models\UniqueId;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\common\models\UniqueId]].
 *
 * @see \common\models\UniqueId
 */
class UniqueIdQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return UniqueId[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return UniqueId|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
