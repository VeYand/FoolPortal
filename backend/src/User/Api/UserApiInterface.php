<?php
declare(strict_types=1);

namespace App\User\Api;

use App\User\Api\Exception\ApiException;
use App\User\App\Query\Data\DetailedUserData;
use App\User\App\Query\Data\GroupData;
use App\User\App\Query\Data\UserData;
use App\User\App\Query\Spec\ListUsersSpec;
use App\User\Domain\Service\Input\CreateUserInput;
use App\User\Domain\Service\Input\UpdateUserInput;

interface UserApiInterface
{
	public function isGroupExists(string $groupId): bool;

	/**
	 * @throws ApiException
	 */
	public function getUserById(string $userId): UserData;

	/**
	 * @throws ApiException
	 */
	public function getDetailedUserById(string $userId): DetailedUserData;

	/**
	 * @throws ApiException
	 */
	public function getUserByEmail(string $email): UserData;

	/**
	 * @throws ApiException
	 */
	public function getUserHashedPassword(string $userId): string;

	/**
	 * @return DetailedUserData[]
	 * @throws ApiException
	 */
	public function listUsers(ListUsersSpec $spec): array;

	/**
	 * @return GroupData[]
	 * @throws ApiException
	 */
	public function listAllGroups(): array;

	/**
	 * @throws ApiException
	 */
	public function createUser(CreateUserInput $input): string;

	/**
	 * @throws ApiException
	 */
	public function updateUser(UpdateUserInput $input): void;

	/**
	 * @throws ApiException
	 */
	public function deleteUser(string $userId): void;

	/**
	 * @throws ApiException
	 */
	public function createGroup(string $groupName): string;

	/**
	 * @throws ApiException
	 */
	public function updateGroup(string $groupId, string $groupName): void;

	/**
	 * @throws ApiException
	 */
	public function deleteGroup(string $groupId): void;

	/**
	 * @param string[] $groupIds
	 * @param string[] $userIds
	 * @throws ApiException
	 */
	public function createGroupMembers(array $groupIds, array $userIds): void;

	/**
	 * @param string[] $groupIds
	 * @param string[] $userIds
	 * @throws ApiException
	 */
	public function deleteGroupMembers(array $groupIds, array $userIds): void;
}