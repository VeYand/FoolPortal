<?php
declare(strict_types=1);

namespace App\User\Api;

use App\User\App\Query\Data\UserData;
use App\User\App\Query\GroupQueryServiceInterface;
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

	/**
	 * @inheritDoc
	 */
	public function getUserByEmail(string $email): UserData
	{
		return $this->userQueryService->getUserByEmail($email);
	}

	/**
	 * @inheritDoc
	 */
	public function getUserHashedPassword(string $userId): string
	{
		return $this->userQueryService->getUserHashedPassword($userId);
	}

	/**
	 * @inheritDoc
	 */
	public function listAllUsers(): array
	{
		return $this->userQueryService->listAllUsers();
	}

	/**
	 * @inheritDoc
	 */
	public function listAllGroups(): array
	{
		return $this->groupQueryService->listAllGroups();
	}

	/**
	 * @inheritDoc
	 */
	public function createUser(CreateUserInput $input): void
	{
		$this->userService->create($input);
	}

	/**
	 * @inheritDoc
	 */
	public function updateUser(UpdateUserInput $input): void
	{
		$this->userService->update($input);
	}

	/**
	 * @inheritDoc
	 */
	public function deleteUser(string $userId): void
	{
		$this->userService->delete($userId);
	}

	/**
	 * @inheritDoc
	 */
	public function createGroup(string $groupName): void
	{
		$this->groupService->create($groupName);
	}

	/**
	 * @inheritDoc
	 */
	public function updateGroup(string $groupId, string $groupName): void
	{
		$this->groupService->update($groupId, $groupName);
	}

	/**
	 * @inheritDoc
	 */
	public function deleteGroup(string $groupId): void
	{
		$this->groupService->delete($groupId);
	}

	/**
	 * @inheritDoc
	 */
	public function addUserToGroup(string $groupId, string $userId): void
	{
		$this->groupMemberService->addUserToGroup($groupId, $userId);
	}

	/**
	 * @inheritDoc
	 */
	public function removeUserFromGroup(string $groupId, string $userId): void
	{
		$this->groupMemberService->removeUserFromGroup($groupId, $userId);
	}
}