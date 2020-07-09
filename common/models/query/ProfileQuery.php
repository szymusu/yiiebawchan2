<?php

namespace common\models\query;

use common\models\Profile;
use common\models\User;
use Yii;

/**
 * This is the ActiveQuery class for [[\common\models\Profile]].
 *
 * @see \common\models\Profile
 */
class ProfileQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Profile[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Profile|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @return ProfileQuery
     */
    public function onlyMine()
    {
        return $this->owner(Yii::$app->user);
    }

    /**
     * @param $user User
     * @return ProfileQuery
     */
    public function owner($user)
    {
        return $this->andWhere(['user_id' => $user->id]);
    }
}
