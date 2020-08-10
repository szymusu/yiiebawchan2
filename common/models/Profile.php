<?php

namespace common\models;

use common\models\query\ProfileQuery;
use Yii;
use yii\base\Exception;
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
	use UniqueLinkUtils;

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
	 * @param string $link
	 * @return Profile|null
	 */
	public static function findByLink($link)
	{
		return Profile::findOne(['link' => $link]);
    }

	/**
	 * @param string $id
	 * @return Profile|null
	 */
	public static function findById($id)
	{
		return Profile::findOne(['profile_id' => $id]);
	}

	/**
     * @return bool
     */
    public function saveNew()
    {
        $this->user_id = Yii::$app->user->id;

	    try
	    {
		    $uid = UniqueId::newRandom($this->link);
	    }
	    catch (Exception $e)
	    {
	    	return false;
	    }

	    $this->profile_id = $uid->id;
        $this->link = $uid->link;

        return $this->save();
    }

//    /**
//     * @param string $previousLink
//     * @return bool
//     */
//    public function processLink($previousLink)
//    {
//
//        if ($this->link === $previousLink)
//        {
//            return true;
//        }
//        else
//        {
//            $duplicate = UniqueId::findOne($this->link);
//        	if ($this->profile_id !== $previousLink && $duplicate == null)
//	        {
//        	    UniqueId::tryDelete($previousLink);
//	        }
//            if (!($this->link))
//            {
//                $this->link = $this->profile_id;
//                return true;
//            }
//            if ($duplicate != null)
//            {
//            	if ($duplicate->id === $this->profile_id)
//	            {
//	            	return true;
//	            }
//	            Yii::$app->session->setFlash('linkExists', 'This link is already in use');
//            	return false;
//            }
//
//        	$uid = new UniqueId();
//        	$uid->id = $this->link;
//        	return $uid->save();
//        }
//    }

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

	/**
	 * @param string $previousLink
	 * @return bool
	 */
	public function linkChange($previousLink)
	{
		return $this->processLink($previousLink, $this->link, $this->profile_id);
	}

	public function setLinkAndId($newLink, $randomId)
	{
		$this->link = $newLink;
		$this->profile_id = $randomId;
	}

	/**
	 * @return bool
	 */
	public function delete()
	{
		UniqueId::tryDelete($this->profile_id);
		UniqueId::tryDelete($this->link);

		return parent::delete();
	}
}
