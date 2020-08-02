<?php

namespace common\models;

use common\models\query\GroupQuery;
use Yii;
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
            [['group_id', 'link'], 'string', 'max' => 16],
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
	 * @throws \yii\base\Exception
	 */
	public function saveNew()
	{
		do
		{
			$randomId = Yii::$app->security->generateRandomString(16);
		} while (Group::find()->where(['group_id' => $randomId])->exists());

		$this->group_id = $randomId;
		$this->fillLink();

		return $this->save();
	}

	/**
	 * @return bool
	 */
	public function fillLink()
	{
		if ($this->link == null)
		{
			$this->link = $this->group_id;
		}
		return true;
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
}