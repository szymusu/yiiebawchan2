<?php

namespace common\models;

use common\models\query\CommentQuery;
use common\models\query\PostQuery;
use common\models\query\ProfileQuery;
use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%comment}}".
 *
 * @property string $comment_id
 * @property string $post_id
 * @property string $profile_id
 * @property string|null $original_comment_id
 * @property string|null $content
 * @property int|null $is_reply
 * @property int $created_at
 *
 * @property Post $post
 * @property Profile $profile
 */
class Comment extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%comment}}';
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
            [['comment_id', 'post_id', 'profile_id'], 'required'],
            [['content'], 'string'],
            [['is_reply', 'created_at'], 'integer'],
            [['comment_id', 'post_id', 'profile_id', 'original_comment_id'], 'string', 'max' => 16],
            [['comment_id'], 'unique'],
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
            'comment_id' => 'Comment ID',
            'post_id' => 'Post ID',
            'profile_id' => 'Profile ID',
            'original_comment_id' => 'Original Comment ID',
            'content' => 'Content',
	        'is_reply' => 'Is Reply',
	        'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Post]].
     *
     * @return ActiveQuery|PostQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::class, ['post_id' => 'post_id']);
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
     * {@inheritdoc}
     * @return CommentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CommentQuery(get_called_class());
    }

    public function setProfile(\common\components\Profile $profile = null): bool
    {
		if ($profile === null)
        {
            $profile = Yii::$app->profile;
        }
                    
		if ($profile->getIsLogged())
		{
			$this->profile_id = $profile->getId();
			return true;
		}
		return false;
    }

    public function saveNew(string $postId,string $replyToId = null,\common\components\Profile $profile = null): bool
    {
		$this->post_id = $postId;
		$this->setProfile($profile);

		try
		{
			$uid = UniqueId::newRandom();
		}
		catch (Exception $e)
		{
			return false;
		}

		$this->comment_id = $uid->id;

		if (!null)
		{
			$this->is_reply = 0;
			$this->original_comment_id = $this->comment_id;
		}
		else
		{
			$this->is_reply = 1;
			$this->original_comment_id = $replyToId;
		}

		return $this->save();
	}

    public function delete(): bool
    {
		UniqueId::tryDelete($this->comment_id);

		$this->deleteReplies();

		return parent::delete();
	}

	private function deleteReplies()
	{
		$replies = Comment::find()->replyTo($this)->all();
		foreach ($replies as $reply)
		{
			$reply->delete();
		}
	}
}
