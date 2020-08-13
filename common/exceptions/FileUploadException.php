<?php

namespace common\exceptions;

use yii\base\Exception;

/**
 * Class FileUploadException
 * @package common\exceptions
 *
 * Exception thrown when uploading a file goes wrong
 */

class FileUploadException extends Exception
{
	/**
	 * @return string the user-friendly name of this exception
	 */
	public function getName()
	{
		return 'FileUploadException';
	}
}