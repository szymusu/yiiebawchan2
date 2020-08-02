<?php

namespace common\models;

use common\models\query\ReactionQuery;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%reaction}}".
 *
 * @property int $reaction_id
 * @property int $type
 * @property string|null $post_id
 * @property string|null $profile_id
 * @property int|null $created_at
 *
 * @property Post $post
 * @property Profile $profile
 */
class Reaction extends ActiveRecord
{
	use TypeArrayUtils;

	/**
	 * @var array<string, int>
	 */
	public static $TYPE = [
		'YES' => 1,
		'NOOO' => 2,
		'XDD' => 3,
		'WTF¿?' => 4,
	];

	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return '{{%reaction}}';
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
			[['created_at', 'type'], 'integer'],
			[['type'], 'required'],
			[['post_id', 'profile_id'], 'string', 'max' => 16],
			[['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::class, 'targetAttribute' => ['post_id' => 'post_id']],
			[['profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profile::class, 'targetAttribute' => ['profile_id' => 'profile_id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'reaction_id' => 'Reaction ID',
			'post_id' => 'Post ID',
			'profile_id' => 'Profile ID',
			'created_at' => 'Created At',
		];
	}

	/**
	 * Gets query for [[Post]].
	 *
	 * @return \yii\db\ActiveQuery|\common\models\query\PostQuery
	 */
	public function getPost()
	{
		return $this->hasOne(Post::class, ['post_id' => 'post_id']);
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
	 * @return ReactionQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new ReactionQuery(get_called_class());
	}
}
