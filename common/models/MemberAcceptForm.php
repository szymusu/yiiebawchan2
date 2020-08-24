<?php


namespace common\models;

use yii\base\Model;

class MemberAcceptForm extends Model
{
	/**
	 * @var string
	 */
	public $profile_id;
	/**
	 * @var bool
	 */
	public $type;

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			['profile_id', 'string'],
			['type', 'boolean'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'profile_id' => '',
			'type' => '',
		];
	}
}