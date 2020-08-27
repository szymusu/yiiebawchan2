<?php

namespace common\models\query;

use common\models\GroupMember;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\common\models\GroupMember]].
 *
 * @see \common\models\GroupMember
 */
class GroupMemberQuery extends ActiveQuery
{
    use ActiveQueryX;

    /**
     * {@inheritdoc}
     * @return GroupMember[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return GroupMember|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

	/**
	 * @param string $groupId
	 * @param string $profileId
	 * @param int $type
	 * @return GroupMemberQuery
	 */
	public function member($groupId, $profileId = null, $type = null)
	{
		if ($type !== null)
		{
			$this->andWhere(['type' => $type]);
		}
		if ($profileId !== null)
		{
			$this->andWhere(['profile_id' => $profileId]);
		}
		return $this->andWhere(['group_id' => $groupId]);
    }

	/**
	 * @param string $groupId
	 * @param string $profileId
	 * @return GroupMemberQuery
	 */
	public function joinRequest($groupId, $profileId = null)
	{
		return $this->member($groupId, $profileId, GroupMember::getTypeNumber('request'));
	}

	/**
	 * @param string $groupId
	 * @param string $profileId
	 * @param int $level
	 * @return bool
	 */
	public function hasPermissionLevel($groupId, $profileId, $level)
	{
		$member = $this->member($groupId, $profileId)->one();
		return ($member !== null && $member->type >= $level);
	}
}
