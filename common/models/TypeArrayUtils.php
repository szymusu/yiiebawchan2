<?php

namespace common\models;

use Exception;

trait TypeArrayUtils
{
    public static function getTypeNumber(string $typeName): int
    {
		return static::$TYPE[$typeName];
	}

    /**
     * @param int $typeNumber
     * @return string
     * @throws Exception
     */
	public static function getTypeName(int $typeNumber): string
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
	public static function getAllTypeNames(): array
    {
		return array_keys(static::$TYPE);
	}

    public static function typeNameExists(string $typeName): bool
    {
		return array_key_exists($typeName, static::$TYPE);
	}

    public static function typeNumberExists(int $typeNumber): bool
    {
		return in_array($typeNumber, static::$TYPE);
	}
}