<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\Reaction]].
 *
 * @see \common\models\Reaction
 */
class ReactionQuery extends \yii\db\ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return \common\models\Reaction[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Reaction|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param $postId string
     * @return ReactionQuery
     */
    public function post($postId)
    {
        return $this->andWhere(['post_id' => $postId]);
    }

    /**
     * @param $type int
     * @return ReactionQuery
     */
    public function type($type)
    {
        return $this->andWhere(['type' => $type]);
    }

	/**
	 * @param $profileId string
	 * @return ReactionQuery
	 */
	public function profile($profileId)
	{
		return $this->andWhere(['profile_id' => $profileId]);
    }

    /**
     * @param $postId string
     * @param $type int
     * @param $profileId string
     * @return ReactionQuery
     */
    public function specific($postId, $type, $profileId)
    {
        return $this->post($postId)->type($type)->profile($profileId);
    }
}
