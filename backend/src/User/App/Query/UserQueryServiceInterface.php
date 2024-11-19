<?php
declare(strict_types=1);

namespace App\User\App\Query;

use App\User\App\Exception\AppException;
use App\User\App\Query\Data\DetailedUserData;
use App\User\App\Query\Data\UserData;

interface UserQueryServiceInterface
{
	/**
	 * @throws AppException
	 */
	public function getUserById(string $userId): UserData;

	/**
	 * @throws AppException
	 */
	public function getDetailedUserById(string $userId): DetailedUserData;

	/**
	 * @throws AppException
	 */
	public function getUserByEmail(string $email): UserData;

	/**
	 * @throws AppException
	 */
	public function getUserHashedPassword(string $userId): string;

	/**
	 * @return DetailedUserData[]
	 */
	public function listAllUsers(): array;
}