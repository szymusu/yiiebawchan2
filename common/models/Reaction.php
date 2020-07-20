<?php

namespace common\models;

use common\models\query\ReactionQuery;
use phpDocumentor\Reflection\Types\Array_;
use Yii;
use yii\behaviors\TimestampBehavior;

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
class Reaction extends \yii\db\ActiveRecord
{
	public static $TYPE = [
		'YES' => [
			'type' => 1,
			'name' => 'YES'
		],
		'NOOO' => [
			'type' => 2,
			'name' => 'NOOO'
		],
		'XDD' => [
			'type' => 3,
			'name' => 'XDD'
		],
		'WTF¿?' => [
			'type' => 4,
			'name' => 'WTF¿?'
		],
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
