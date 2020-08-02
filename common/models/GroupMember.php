<?php

namespace common\models;

use common\models\query\GroupMemberQuery;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%group_member}}".
 *
 * @property int $id
 * @property string|null $group_id
 * @property string|null $profile_id
 * @property int|null $type
 * @property int|null $created_at
 *
 * @property Group $group
 * @property Profile $profile
 */
class GroupMember extends ActiveRecord
{
	use TypeArrayUtils;

	/**
	 * @var array<string, int>
	 */
	private static $TYPE = [
		'banned' => -1,
		'request' => 0,
		'muted' => 1,
		'member' => 2,
		'moderator' => 3,
		'admin' => 4,
		'owner' => 5,
	];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%group_member}}';
    }

	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			[
				'class' => TimestampBehavior::class,
				'updatedAtAttribute' => false,
			]
		];
	}

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'created_at'], 'integer'],
            [['group_id', 'profile_id'], 'string', 'max' => 16],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::class, 'targetAttribute' => ['group_id' => 'group_id']],
            [['profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profile::class, 'targetAttribute' => ['profile_id' => 'profile_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_id' => 'Group ID',
            'profile_id' => 'Profile ID',
            'type' => 'Type',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Group]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\GroupQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Group::class, ['group_id' => 'group_id']);
    }

    /**
     * Gets query for [[Profile]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ProfileQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::class, ['profile_id' => 'profile_id']);
    }

    /**
     * {@inheritdoc}
     * @return GroupMemberQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GroupMemberQuery(get_called_class());
    }

	/**
	 * @param string $groupId
	 * @param string $profileId
	 * @return bool
	 */
	public static function hasJoinRequest($groupId, $profileId)
	{
		return GroupMember::find()->joinRequest($groupId, $profileId)->exists();
    }

	/**
	 * @param string $groupId
	 * @param string $profileId
	 * @return bool
	 */
	public function newJoinRequest($groupId, $profileId)
	{
		$this->profile_id = $profileId;
		$this->group_id = $groupId;
		$this->type = GroupMember::getTypeNumber('request');

		return $this->save();
    }
}
