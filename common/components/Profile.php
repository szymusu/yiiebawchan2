<?php


namespace common\components;


use common\models\Profile as ProfileModel;
use yii\base\Component;
use yii\web\ForbiddenHttpException;

/**
 * Class Profile
 * @package common\components
 */
class Profile extends Component
{
    /**
     * @var $_profile ProfileModel
     */
    private $_profile;

    /**
     * @return ProfileModel
     */

    public function init()
    {
        parent::init();

        $model = ProfileModel::find()->onlyMine()->orderBy('last_login DESC')->one();
        $this->_profile = $model;
        return $this->get();
    }

    /**
     * @return ProfileModel | null
     */
    public function get()
    {
        return $this->_profile;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->_profile->profile_id;
    }

    /**
     * @return bool
     */
    public function getIsLogged()
    {
        return ($this->get() != null);
    }

    public function getUrl()
    {
        if ($this->getIsLogged())
        {
            return ['/profile/view/' . $this->get()->link];
        }
        else return [];
    }

    /**
     * @param $profile ProfileModel
     * @throws ForbiddenHttpException
     */
    public function switchTo($profile)
    {
        if ($profile->isMine())
        {
            $this->_profile = $profile;
            $profile->loginTimestamp();
            return;
        }
        throw new ForbiddenHttpException('Operation not permitted');
    }
}