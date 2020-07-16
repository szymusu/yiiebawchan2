<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\Reaction]].
 *
 * @see \common\models\Reaction
 */
class ReactionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

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
     * @param $postId string
     * @param $type int
     * @return ReactionQuery
     */
    public function specific($postId, $type)
    {
        return $this->post($postId)->type($type);
    }
}
