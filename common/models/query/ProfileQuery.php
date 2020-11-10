<?php

namespace common\models\query;

use common\models\Profile;
use Yii;
use yii\db\ActiveQuery;
use yii\web\User;

/**
 * This is the ActiveQuery class for [[\common\models\Profile]].
 *
 * @see \common\models\Profile
 */
class ProfileQuery extends ActiveQuery
{
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

    public function owner(User $user): ProfileQuery
    {
        return $this->andWhere(['user_id' => $user->id]);
    }
}
