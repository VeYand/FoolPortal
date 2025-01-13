<?php
declare(strict_types=1);

namespace App\User\App\Exception;

use App\User\Domain\Exception\DomainException;

class AppException extends DomainException
{
	public const int INVALID_PAGINATION = 100;
	public const int INVALID_ORDER_FILED = 101;
}