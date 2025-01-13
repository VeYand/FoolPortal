<?php
declare(strict_types=1);

namespace App\User\App\Query;

use App\Common\Uuid\UuidInterface;
use App\User\App\Exception\AppException;
use App\User\App\Query\Data\DetailedUserData;
use App\User\App\Query\Data\ListUsersOutput;
use App\User\App\Query\Data\UserData;
use App\User\App\Query\Spec\ListUsersSpec;

interface UserQueryServiceInterface
{
	/**
	 * @throws AppException
	 */
	public function getUserById(UuidInterface $userId): UserData;

	/**
	 * @throws AppException
	 */
	public function getDetailedUserById(UuidInterface $userId): DetailedUserData;

	/**
	 * @throws AppException
	 */
	public function getUserByEmail(string $email): UserData;

	/**
	 * @throws AppException
	 */
	public function getUserHashedPassword(UuidInterface $userId): string;

	/**
	 * @throws AppException
	 */
	public function listUsers(ListUsersSpec $spec): ListUsersOutput;
}