<?php

namespace common\models;

use Exception;

trait TypeArrayUtils
{
	/**
	 * @param string $typeName
	 * @return int
	 */
	public static function getTypeNumber($typeName)
	{
		return static::$TYPE[$typeName];
	}

	/**
	 * @param int $typeNumber
	 * @return string
	 * @throws Exception
	 */
	public static function getTypeName($typeNumber)
	{
		$result = array_search($typeNumber, static::$TYPE);
		if ($result === false)
		{
			throw new Exception('Not valid type number');
		}
		else
		{
			return $result;
		}
	}

	/**
	 * @return array<string>
	 */
	public static function getAllTypeNames()
	{
		return array_keys(static::$TYPE);
	}
}