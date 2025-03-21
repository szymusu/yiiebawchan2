<?php

namespace common\models;

use common\models\query\UniqueIdQuery;
use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%unique_id}}".
 *
 * @property string $id
 */
class UniqueId extends ActiveRecord
{
	/**
	 * @var string
	 */
	public string $link;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%unique_id}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'string', 'max' => 16],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
        ];
    }

    /**
     * {@inheritdoc}
     * @return UniqueIdQuery the active query used by this AR class.
     */
    public static function find(): UniqueIdQuery
    {
        return new UniqueIdQuery(get_called_class());
    }

    public static function tryDelete(string $value)
	{
		$query = static::findOne($value);
		if ($query != null)
		{
			$query->delete();
		}
    }

	/**
	 * @return UniqueId
	 * @throws Exception
	 */
	public function randomize(): UniqueId
    {
		do
		{
			$randomId = Yii::$app->security->generateRandomString(16);
		} while (static::find()->where(['id' => $randomId])->exists());

		$this->id = $randomId;
		return $this;
    }

    /**
     * @param string|null $link
     * @return UniqueId
     * @throws Exception
     */
	public static function newRandom(?string $link = null): UniqueId
    {
		$uid = new static();
		$uid->randomize();

		if ($link === null)
		{
			$uid->link = $uid->id;
		}
		else
		{
			$uid->link = $link;
			$linkId = new UniqueId();
			$linkId->id = $link;
			if (!($linkId->save()))
			{
				throw new Exception();
			}
		}

		if (!($uid->save()))
		{
			throw new Exception();
		}

		return $uid;
    }
}
