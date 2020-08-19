<?php

namespace common\models;

use common\models\query\GroupQuery;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%group}}".
 *
 * @property string $group_id
 * @property string|null $link
 * @property string $name
 * @property string|null $description
 * @property int|null $type
 * @property int|null $created_at
 */
class Group extends ActiveRecord
{
	use TypeArrayUtils;
	use UniqueLinkUtils;

	/**
	 * @var array<string, int>
	 */
	public static $TYPE = [
		'public' => 1,
		'private' => 2,
		'secret' => 3,
	];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%group}}';
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
            [['group_id', 'name'], 'required'],
            [['description'], 'string'],
            [['type', 'created_at'], 'integer'],
            [['group_id'], 'string', 'max' => 16],
            [['link'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 64],
            [['link'], 'unique'],
            [['group_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'group_id' => 'Group ID',
            'link' => 'Link',
            'name' => 'Name',
            'description' => 'Description',
            'type' => 'Type',
            'created_at' => 'Created At',
        ];
    }

    /**
     * {@inheritdoc}
     * @return GroupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GroupQuery(get_called_class());
    }

	/**
	 * @return bool
	 */
	public function saveNew()
	{
		try
		{
			$uid = UniqueId::newRandom($this->link);
		}
		catch (Exception $e)
		{
			return false;
		}

		$this->group_id = $uid->id;
		$this->link = $uid->link;

		return $this->save();
	}

	/**
	 * @param string $newLink
	 * @param string $randomId
	 */
	public function setLinkAndId($newLink, $randomId)
	{
		$this->link = $newLink;
		$this->group_id = $randomId;
	}

	/**
	 * @param string $previousLink
	 * @return bool
	 */
	public function linkChange($previousLink)
	{
		return $this->processLink($previousLink, $this->link, $this->group_id);
	}

	/**
	 * @param string $link
	 * @return Group|null
	 */
	public static function findByLink($link)
	{
		return Group::findOne(['link' => $link]);
	}

	/**
	 * @param string $id
	 * @return Group|null
	 */
	public static function findById($id)
	{
		return Group::findOne(['group_id' => $id]);
	}

	/**
	 * @param string $profileId
	 * @return bool
	 */
	public function hasJoinRequest($profileId)
	{
		return GroupMember::hasJoinRequest($this->group_id, $profileId);
	}

	/**
	 * @param string $profileId
	 * @return bool
	 */
	public function isBanned($profileId)
	{
		return $this->isMemberType($profileId, GroupMember::getTypeNumber('banned'));
	}

	/**
	 * @param string $profileId
	 * @return bool
	 */
	public function isMuted($profileId)
	{
		return $this->isMemberType($profileId, GroupMember::getTypeNumber('muted'));
	}

	/**
	 * @param string $profileId
	 * @param int $type
	 * @return bool
	 */
	public function isMemberType($profileId, $type)
	{
		return GroupMember::find()->member($this->group_id, $profileId, $type)->exists();
	}

	/**
	 * @param string $profileId
	 * @return bool
	 */
	public function isMember($profileId)
	{
		return GroupMember::find()->member($this->group_id, $profileId)->exists();
	}

	/**
	 * @param string $profileId
	 * @param int $permissionLevel
	 * @return bool
	 */
	public function hasPermissions($profileId, $permissionLevel)
	{
		return (
			$this->isMember($profileId) &&
			GroupMember::find()->hasPermissionLevel($this->group_id, $profileId, $permissionLevel)
		);
	}

	/**
	 * @param string $profileId
	 * @return bool
	 * Admin also is moderator btw
	 */
	public function isModerator($profileId)
	{
		return $this->hasPermissions($profileId, GroupMember::getTypeNumber('moderator'));
	}

	/**
	 * @param string $profileId
	 * @return bool
	 */
	public function isAdmin($profileId)
	{
		return $this->hasPermissions($profileId, GroupMember::getTypeNumber('admin'));
	}

	/**
	 * @param string $profileId
	 * @return bool
	 */
	public function isOwner($profileId)
	{
		return $this->hasPermissions($profileId, GroupMember::getTypeNumber('owner'));
	}

	/**
	 * @param string $profileId
	 * @return bool
	 */
	public function isAllowedMember($profileId)
	{
		return (
			$this->isMember($profileId) &&
			GroupMember::find()->hasPermissionLevel($this->group_id, $profileId, GroupMember::getTypeNumber('muted'))
		);
	}

	/**
	 * @param string $profileId
	 * @return bool
	 */
	public function isAllowedToPost($profileId)
	{
		return (
			$this->isMember($profileId) &&
			GroupMember::find()->hasPermissionLevel($this->group_id, $profileId, GroupMember::getTypeNumber('member'))
		);
	}

	/**
	 * @return bool
	 */
	public function delete()
	{
		UniqueId::tryDelete($this->group_id);
		UniqueId::tryDelete($this->link);

		return parent::delete();
	}
}