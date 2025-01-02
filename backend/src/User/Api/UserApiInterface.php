<?php
declare(strict_types=1);

namespace App\User\Api;

use App\Common\Uuid\UuidInterface;
use App\User\Api\Exception\ApiException;
use App\User\App\Query\Data\DetailedUserData;
use App\User\App\Query\Data\GroupData;
use App\User\App\Query\Data\UserData;
use App\User\App\Query\Spec\ListGroupsSpec;
use App\User\App\Query\Spec\ListUsersSpec;
use App\User\Domain\Service\Input\CreateUserInput;
use App\User\Domain\Service\Input\UpdateUserInput;

interface UserApiInterface
{
	public function isGroupExists(UuidInterface $groupId): bool;

	/**
	 * @throws ApiException
	 */
	public function getUserById(UuidInterface $userId): UserData;

	/**
	 * @throws ApiException
	 */
	public function getDetailedUserById(UuidInterface $userId): DetailedUserData;

	/**
	 * @throws ApiException
	 */
	public function getUserByEmail(string $email): UserData;

	/**
	 * @throws ApiException
	 */
	public function getUserHashedPassword(UuidInterface $userId): string;

	/**
	 * @return DetailedUserData[]
	 * @throws ApiException
	 */
	public function listUsers(ListUsersSpec $spec): array;

	/**
	 * @return GroupData[]
	 * @throws ApiException
	 */
	public function listGroups(ListGroupsSpec $spec): array;

	/**
	 * @throws ApiException
	 */
	public function createUser(CreateUserInput $input): UuidInterface;

	/**
	 * @throws ApiException
	 */
	public function updateUser(UpdateUserInput $input): void;

	/**
	 * @throws ApiException
	 */
	public function deleteUser(UuidInterface $userId): void;

	/**
	 * @throws ApiException
	 */
	public function createGroup(string $groupName): UuidInterface;

	/**
	 * @throws ApiException
	 */
	public function updateGroup(UuidInterface $groupId, string $groupName): void;

	/**
	 * @throws ApiException
	 */
	public function deleteGroup(UuidInterface $groupId): void;

	/**
	 * @param UuidInterface[] $groupIds
	 * @param UuidInterface[] $userIds
	 * @throws ApiException
	 */
	public function createGroupMembers(array $groupIds, array $userIds): void;

	/**
	 * @param UuidInterface[] $groupIds
	 * @param UuidInterface[] $userIds
	 * @throws ApiException
	 */
	public function deleteGroupMembers(array $groupIds, array $userIds): void;
}