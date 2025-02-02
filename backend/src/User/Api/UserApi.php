<?php
declare(strict_types=1);

namespace App\User\Api;

use App\Common\Uuid\UuidInterface;
use App\User\Api\Exception\ApiException;
use App\User\App\Exception\AppException;
use App\User\App\Query\Data\DetailedUserData;
use App\User\App\Query\Data\ListUsersOutput;
use App\User\App\Query\Data\UserData;
use App\User\App\Query\GroupQueryServiceInterface;
use App\User\App\Query\Spec\ListGroupsSpec;
use App\User\App\Query\Spec\ListUsersSpec;
use App\User\App\Query\UserQueryServiceInterface;
use App\User\App\Service\GroupMemberService;
use App\User\App\Service\GroupService;
use App\User\App\Service\UserService;
use App\User\Domain\Service\Input\CreateUserInput;
use App\User\Domain\Service\Input\UpdateUserInput;

readonly class UserApi implements UserApiInterface
{
	public function __construct(
		private UserQueryServiceInterface  $userQueryService,
		private GroupQueryServiceInterface $groupQueryService,
		private UserService                $userService,
		private GroupService               $groupService,
		private GroupMemberService         $groupMemberService,
	)
	{
	}

	public function isGroupExists(UuidInterface $groupId): bool
	{
		return $this->groupQueryService->isGroupExists($groupId);
	}

	/**
	 * @inheritDoc
	 */
	public function getUserById(UuidInterface $userId): UserData
	{
		return self::tryExecute(function () use ($userId)
		{
			return $this->userQueryService->getUserById($userId);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function getDetailedUserById(UuidInterface $userId): DetailedUserData
	{
		return self::tryExecute(function () use ($userId)
		{
			return $this->userQueryService->getDetailedUserById($userId);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function getUserByEmail(string $email): UserData
	{
		return self::tryExecute(function () use ($email)
		{
			return $this->userQueryService->getUserByEmail($email);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function getUserHashedPassword(UuidInterface $userId): string
	{
		return self::tryExecute(function () use ($userId)
		{
			return $this->userQueryService->getUserHashedPassword($userId);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function listUsers(ListUsersSpec $spec): ListUsersOutput
	{
		return self::tryExecute(function () use ($spec)
		{
			return $this->userQueryService->listUsers($spec);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function listGroups(ListGroupsSpec $spec): array
	{
		return self::tryExecute(function () use ($spec)
		{
			return $this->groupQueryService->listGroups($spec);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function createUser(CreateUserInput $input): UuidInterface
	{
		return self::tryExecute(function () use ($input)
		{
			return $this->userService->create($input);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function updateUser(UpdateUserInput $input): void
	{
		self::tryExecute(function () use ($input)
		{
			$this->userService->update($input);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function deleteUser(UuidInterface $userId): void
	{
		self::tryExecute(function () use ($userId)
		{
			$this->userService->delete($userId);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function createGroup(string $groupName): UuidInterface
	{
		return self::tryExecute(function () use ($groupName)
		{
			return $this->groupService->create($groupName);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function updateGroup(UuidInterface $groupId, string $groupName): void
	{
		self::tryExecute(function () use ($groupId, $groupName)
		{
			$this->groupService->update($groupId, $groupName);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function deleteGroup(UuidInterface $groupId): void
	{
		self::tryExecute(function () use ($groupId)
		{
			$this->groupService->delete($groupId);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function createGroupMembers(array $groupIds, array $userIds): void
	{
		self::tryExecute(function () use ($groupIds, $userIds)
		{
			$this->groupMemberService->createGroupMembers($groupIds, $userIds);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function deleteGroupMembers(array $groupIds, array $userIds): void
	{
		self::tryExecute(function () use ($groupIds, $userIds)
		{
			$this->groupMemberService->deleteGroupMembers($groupIds, $userIds);
		});
	}

	/**
	 * @throws ApiException
	 */
	private static function tryExecute(callable $callback): mixed
	{
		try
		{
			return $callback();
		}
		catch (AppException $e)
		{
			throw new ApiException($e->getMessage(), $e->getCode(), $e);
		}
	}
}