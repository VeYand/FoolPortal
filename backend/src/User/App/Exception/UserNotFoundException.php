<?php
declare(strict_types=1);

namespace App\User\App\Exception;

use App\Common\Exception\AppException;

class UserNotFoundException extends AppException
{
	private const string DEFAULT_MESSAGE = 'User not found';

	public function __construct(string $message = self::DEFAULT_MESSAGE, int $code = 404)
	{
		parent::__construct($message, $code);
	}
}