<?php
declare(strict_types=1);

namespace App\Security\App\Exception;

class AppException extends \Exception
{
	public const int INTERNAL = 0;
	public const int USER_NOT_FOUND = 1;

	public function __construct(string $message, int $code = self::INTERNAL, \Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}