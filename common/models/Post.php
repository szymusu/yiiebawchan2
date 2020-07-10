<?php

namespace common\models;

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
            [['post_id', 'profile_id'], 'string', 'max' => 16],
            [['post_id'], 'unique'],
            [['profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profile::class, 'targetAttribute' => ['profile_id' => 'profile_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
     * @return bool
     */
    public function isMine()
    {
        return ($this->user_id === Yii::$app->user->id);
    }

    /**
     * {@inheritdoc}
     * @return PostQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PostQuery(get_called_class());
    }
    
    /**
     * @return bool
     * @throws Exception
     * @param $profile Profile
     */
    public function saveNew($profile)
    {
        $this->user_id = Yii::$app->user->id;
        $this->profile_id = $profile->profile_id;
        do
        {
            $randomId = Yii::$app->security->generateRandomString(16);
        } while (Post::find()->where(['post_id' => $randomId])->exists());

        $this->post_id = $randomId;

        return $this->save();
    }
}
