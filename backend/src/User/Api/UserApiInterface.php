<?php
declare(strict_types=1);

namespace App\User\Api;

use App\Common\Exception\AppException;
use App\User\App\Query\Data\UserData;

interface UserApiInterface
{
	/**
	 * @throws AppException
	 */
	public function getUserByEmail(string $email): UserData;

	/**
	 * @throws AppException
	 */
	public function getUserHashedPassword(string $userId): string;
}