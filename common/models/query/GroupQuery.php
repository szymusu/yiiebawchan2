<?php

namespace common\models\query;

use common\models\Group;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\common\models\Group]].
 *
 * @see \common\models\Group
 */
class GroupQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return Group[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Group|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
