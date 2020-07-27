<?php


namespace common\models\query;


trait ActiveQueryX
{
	public function latest()
	{
		return $this->orderBy(['created_at' => SORT_DESC]);
	}
}