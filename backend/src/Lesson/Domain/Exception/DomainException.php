<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Exception;

class DomainException extends \Exception
{
	public const int INTERNAL = 0;
	public const int INVALID_LESSON_DURATION = 1;
	public const int INVALID_LESSON_START_TIME = 2;
	public const int LESSON_DATE_ALREADY_PASSED = 3;
	public const int LOCATION_NOT_FOUND = 4;
	public const int LESSON_NOT_FOUND = 5;
	public const int ATTACHMENT_NOT_FOUND = 6;
	public const int LESSON_ATTACHMENT_ALREADY_EXISTS = 7;
	public const int INVALID_BASE_64_DATA = 8;
	public const int LOCATION_NAME_IS_NOT_UNIQUE = 9;


	public function __construct(string $message, int $code = self::INTERNAL, \Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}