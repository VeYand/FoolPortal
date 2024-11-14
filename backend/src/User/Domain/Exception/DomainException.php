<?php
declare(strict_types=1);

namespace App\User\Domain\Exception;

class DomainException extends \Exception
{
	public const int INTERNAL = 0;
	public const int USER_NOT_FOUND = 1;
	public const int GROUP_NOT_FOUND = 2;
	public const int INVALID_BASE_64_DATA = 3;
	public const int UNSUPPORTED_IMAGE_FORMAT = 4;
	public const int PASSWORD_IS_TOO_LONG = 5;
	public const int EMAIL_IS_NOT_UNIQUE = 6;
	public const int GROUP_MEMBER_ALREADY_EXISTS = 7;

	public function __construct(string $message, int $code = self::INTERNAL, \Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}