<?php
declare(strict_types=1);

namespace App\User\Api\Exception;

use App\User\App\Exception\AppException;

class ApiException extends AppException
{
	public const int INVALID_USER_ROLE = 201;
}