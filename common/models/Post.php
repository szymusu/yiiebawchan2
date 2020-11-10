<?php

namespace common\models;

use common\models\query\GroupQuery;
use common\models\query\PostQuery;
use common\models\query\ProfileQuery;
use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%post}}".
 *
 * @property string $post_id
 * @property int|null $user_id
 * @property string $profile_id
 * @property string $group_id
 * @property string|null $content
 * @property int $created_at
 *
 * @property Profile $profile
 * @property User $user
 */
class Post extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%post}}';
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
            [['post_id', 'profile_id'], 'required'],
            [['user_id', 'created_at'], 'integer'],
            [['content'], 'string'],
            [['post_id', 'profile_id', 'group_id'], 'string', 'max' => 16],
            [['post_id'], 'unique'],
            [['profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profile::class, 'targetAttribute' => ['profile_id' => 'profile_id']],
	        [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
	        [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::class, 'targetAttribute' => ['group_id' => 'group_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'post_id' => 'Post ID',
	        'user_id' => 'User ID',
	        'group_id' => 'Group ID',
            'profile_id' => 'Profile ID',
            'content' => 'Content',
        ];
    }

    /**
     * Gets query for [[Profile]].
     *
     * @return ActiveQuery|ProfileQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::class, ['profile_id' => 'profile_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

	/**
	 * Gets query for [[Group]].
	 *
	 * @return ActiveQuery|GroupQuery
	 */
	public function getGroup()
	{
		return $this->hasOne(Group::class, ['group_id' => 'group_id']);
	}

    public function groupName(): string
    {
		return $this->getGroup()->one()->name;
	}

    public function groupLink(): string
    {
		return $this->getGroup()->one()->link;
	}

    public function isMine(): bool
    {
        return ($this->profile_id === Yii::$app->profile->getId());
    }

    /**
     * {@inheritdoc}
     * @return PostQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PostQuery(get_called_class());
    }

    public static function findById(string $id) : Post
	{
		return static::find()->andWhere(['post_id' => $id])->one();
	}

    public function canIAccess() : bool
    {
        return $this->getGroup()->one()->isAllowedToPost(Yii::$app->profile->getId());
    }

    public function saveNew(Profile $profile, Group $group) : bool
    {
        $this->user_id = Yii::$app->user->id;
        $this->profile_id = $profile->profile_id;
        $this->group_id = $group->group_id;

	    try
	    {
		    $uid = UniqueId::newRandom();
	    }
	    catch (Exception $e)
	    {
		    return false;
	    }

        $this->post_id = $uid->id;

        return $this->save();
    }

    public function getReactionCount(string $typeName = null) : int
    {
    	if ($typeName === null)
	    {
            return Reaction::find()->post($this->post_id)->count();
	    }
        return Reaction::find()->andWhere(['type' => Reaction::getTypeNumber($typeName)])->post($this->post_id)->count();
    }

    public function getReactionsFromProfile(string $profileId): array
    {
		if (empty($profileId)) return [];
		$reactions = [];
		foreach (Reaction::getAllTypeNames() as $typeName)
		{
			$reactions[$typeName] = Reaction::find()
				->specific($this->post_id, Reaction::getTypeNumber($typeName), $profileId)->exists();
		}
		return $reactions;
    }

    public function getMyReactions(): array
    {
		return $this->getReactionsFromProfile(Yii::$app->profile->getId());
	}

    public function delete(): bool
    {
		UniqueId::tryDelete($this->post_id);
		return parent::delete();
	}
}
