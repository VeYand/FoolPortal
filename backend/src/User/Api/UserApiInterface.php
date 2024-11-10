<?php
declare(strict_types=1);

namespace App\User\Api;

use App\Common\Exception\AppException;
use App\User\App\Query\Data\DetailedUserData;
use App\User\App\Query\Data\GroupData;
use App\User\App\Query\Data\UserData;
use App\User\Domain\Service\Input\CreateUserInput;
use App\User\Domain\Service\Input\UpdateUserInput;

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

	/**
	 * @return DetailedUserData[]
	 */
	public function listAllUsers(): array;

	/**
	 * @return GroupData[]
	 */
	public function listAllGroups(): array;

	/**
	 * @throws AppException
	 */
	public function createUser(CreateUserInput $input): void;

	/**
	 * @throws AppException
	 */
	public function updateUser(UpdateUserInput $input): void;

	/**
	 * @throws AppException
	 */
	public function deleteUser(string $userId): void;

	/**
	 * @throws AppException
	 */
	public function createGroup(string $groupName): void;

	/**
	 * @throws AppException
	 */
	public function updateGroup(string $groupId, string $groupName): void;

	/**
	 * @throws AppException
	 */
	public function deleteGroup(string $groupId): void;

	/**
	 * @throws AppException
	 */
	public function addUserToGroup(string $groupId, string $userId): void;

	/**
	 * @throws AppException
	 */
	public function removeUserFromGroup(string $groupId, string $userId): void;
}