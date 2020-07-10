<?php

namespace common\models;

use common\models\query\ProfileQuery;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%profile}}".
 *
 * @property string $profile_id
 * @property string|null $link
 * @property string $name
 * @property int|null $user_id
 * @property string|null $description
 * @property int $last_login
 *
 * @property User $user
 */
class Profile extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%profile}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['profile_id', 'name'], 'required'],
            [['user_id', 'last_login'], 'integer'],
            [['description'], 'string'],
            [['profile_id', 'link'], 'string', 'max' => 16],
            [['name'], 'string', 'max' => 64],
            [['link'], 'unique'],
            [['profile_id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'profile_id' => 'Profile ID',
            'link' => 'Link',
            'name' => 'Name',
            'user_id' => 'User ID',
            'description' => 'Description',
            'last_login' => 'Last login',
        ];
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
     * {@inheritdoc}
     * @return ProfileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProfileQuery(get_called_class());
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     */
    public function newRecord()
    {
        $this->user_id = Yii::$app->user->id;
        do
        {
            $randomId = Yii::$app->security->generateRandomString(16);
        } while (Profile::find()->where(['profile_id' => $randomId])->exists());

        $this->profile_id = $randomId;
        $this->fillLink();

        return true;
    }

    /**
     * @return bool
     */
    public function fillLink()
    {
        if ($this->link == null)
        {
            $this->link = $this->profile_id;
        }
        return true;
    }

    /**
     * @param $user User
     * @return bool
     */
    public function isOwnedBy($user)
    {
        return ($this->user_id == $user->id);
    }

    /**
     * @return bool
     */
    public function isMine()
    {
        return $this->isOwnedBy(Yii::$app->user);
    }

    public function loginTimestamp()
    {
        $this->last_login = time();
        $this->save(true, ['last_login']);
    }
}
