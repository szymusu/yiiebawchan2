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

	public function member(string $groupId, string $profileId = null, string $type = null): GroupMemberQuery
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

	public function joinRequest(string $groupId, string $profileId = null): GroupMemberQuery
    {
		return $this->member($groupId, $profileId, GroupMember::getTypeNumber('request'));
	}

    public function hasPermissionLevel(string $groupId, string $profileId, int $level): bool
    {
		$member = $this->member($groupId, $profileId)->one();
		return ($member !== null && $member->type >= $level);
	}
}
