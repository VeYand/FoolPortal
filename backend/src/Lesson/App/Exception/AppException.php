<?php
declare(strict_types=1);

namespace App\Lesson\App\Exception;

use App\Lesson\Domain\Exception\DomainException;

class AppException extends DomainException
{
	public const int COURSE_NOT_EXISTS = 101;
}