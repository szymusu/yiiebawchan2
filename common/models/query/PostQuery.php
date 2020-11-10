<?php

namespace common\models\query;

use common\models\Post;
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
     * @return Post[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Post|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function group(string $groupId): PostQuery
    {
		return $this->andWhere(['group_id' => $groupId]);
    }
}
