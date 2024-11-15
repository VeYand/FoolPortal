<?php
declare(strict_types=1);

namespace App\Subject\App\Exception;

use App\Subject\Domain\Exception\DomainException;

class AppException extends DomainException
{
	public const int GROUP_NOT_EXISTS = 101;
	public const int USER_NOT_EXISTS = 102;
	public const int USER_IS_NOT_TEACHER = 103;
}