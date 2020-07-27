<?php

namespace common\models\query;


use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\common\models\Post]].
 *
 * @see \common\models\Post
 */
class PostQuery extends ActiveQuery
{
    use ActiveQueryX;

    /**
     * {@inheritdoc}
     * @return \common\models\Post[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Post|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
