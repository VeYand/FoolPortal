<?php
declare(strict_types=1);

namespace App\User\Api;

use App\User\Api\Exception\ApiException;
use App\User\App\Exception\AppException;
use App\User\App\Query\Data\DetailedUserData;
use App\User\App\Query\Data\UserData;
use App\User\App\Query\GroupQueryServiceInterface;
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

	public function isGroupExists(string $groupId): bool
	{
		return $this->groupQueryService->isGroupExists($groupId);
	}

	/**
	 * @inheritDoc
	 */
	public function getUserById(string $userId): UserData
	{
		return self::tryExecute(function () use ($userId)
		{
			return $this->userQueryService->getUserById($userId);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function getDetailedUserById(string $userId): DetailedUserData
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
	public function getUserHashedPassword(string $userId): string
	{
		return self::tryExecute(function () use ($userId)
		{
			return $this->userQueryService->getUserHashedPassword($userId);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function listUsers(ListUsersSpec $spec): array
	{
		return self::tryExecute(function () use ($spec)
		{
			return $this->userQueryService->listUsers($spec);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function listAllGroups(): array
	{
		return self::tryExecute(function ()
		{
			return $this->groupQueryService->listAllGroups();
		});
	}

	/**
	 * @inheritDoc
	 */
	public function createUser(CreateUserInput $input): void
	{
		self::tryExecute(function () use ($input)
		{
			$this->userService->create($input);
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
	public function deleteUser(string $userId): void
	{
		self::tryExecute(function () use ($userId)
		{
			$this->userService->delete($userId);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function createGroup(string $groupName): void
	{
		self::tryExecute(function () use ($groupName)
		{
			$this->groupService->create($groupName);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function updateGroup(string $groupId, string $groupName): void
	{
		self::tryExecute(function () use ($groupId, $groupName)
		{
			$this->groupService->update($groupId, $groupName);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function deleteGroup(string $groupId): void
	{
		self::tryExecute(function () use ($groupId)
		{
			$this->groupService->delete($groupId);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function addUserToGroup(string $groupId, string $userId): void
	{
		self::tryExecute(function () use ($groupId, $userId)
		{
			$this->groupMemberService->addUserToGroup($groupId, $userId);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function removeUserFromGroup(string $groupId, string $userId): void
	{
		self::tryExecute(function () use ($groupId, $userId)
		{
			$this->groupMemberService->removeUserFromGroup($groupId, $userId);
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