<?php
declare(strict_types=1);

namespace App\User\App\Query;

use App\Common\Exception\AppException;
use App\User\App\Query\Data\UserData;

interface UserQueryServiceInterface
{
	/**
	 * @throws AppException
	 */
	public function getUserByEmail(string $email): UserData;

	public function getUserHashedPassword(string $userId): string;

	/**
	 * @return UserData[]
	 */
	public function listAllUsers(): array;
}