<?php


namespace common\models\query;


class ActiveQuery extends \yii\db\ActiveQuery
{
	public function latest()
	{
		return $this->orderBy(['created_at' => SORT_DESC]);
	}
}