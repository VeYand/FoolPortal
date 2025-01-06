<?php
declare(strict_types=1);

namespace App\Subject\Domain\Exception;

class DomainException extends \Exception
{
	public const int INTERNAL = 0;
	public const int TEACHER_SUBJECT_NOT_FOUND = 1;
	public const int COURSE_ALREADY_EXISTS = 2;
	public const int SUBJECT_NOT_FOUND = 3;
	public const int TEACHER_SUBJECT_ALREADY_EXISTS = 4;
	public const int SUBJECT_NAME_IS_NOT_UNIQUE = 6;

	public function __construct(string $message, int $code = self::INTERNAL, \Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}