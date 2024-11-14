<?php
declare(strict_types=1);

namespace App\Session\App\Exception;

class AppException extends \Exception
{
	public const int INTERNAL = 0;
	public const int NOT_AUTHORIZED = 1;

	public function __construct(string $message, int $code = self::INTERNAL, \Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}