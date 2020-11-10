<?php

namespace common\models\query;

use common\models\Reaction;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\common\models\Reaction]].
 *
 * @see \common\models\Reaction
 */
class ReactionQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return Reaction[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Reaction|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function post(string $postId): ReactionQuery
    {
        return $this->andWhere(['post_id' => $postId]);
    }

    public function type(int $type): ReactionQuery
    {
        return $this->andWhere(['type' => $type]);
    }

    public function profile(string $profileId): ReactionQuery
    {
		return $this->andWhere(['profile_id' => $profileId]);
    }

    public function specific(string $postId, int $type, string $profileId): ReactionQuery
    {
        return $this->post($postId)->type($type)->profile($profileId);
    }
}
