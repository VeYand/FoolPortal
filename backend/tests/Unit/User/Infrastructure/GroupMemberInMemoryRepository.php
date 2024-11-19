<?php
declare(strict_types=1);

namespace App\Tests\Unit\User\Infrastructure;

use App\User\Domain\Model\GroupMember;
use App\User\Domain\Repository\GroupMemberRepositoryInterface;

class GroupMemberInMemoryRepository implements GroupMemberRepositoryInterface
{
	/** @var array<string, GroupMember> */
	private array $groupMembers = [];

	public function find(string $groupId, string $userId): ?GroupMember
	{
		foreach ($this->groupMembers as $groupMember)
		{
			if ($groupMember->getGroupId() === $groupId && $groupMember->getUserId() === $userId)
			{
				return $groupMember;
			}
		}

		return null;
	}

	/**
	 * @inheritDoc
	 */
	public function findByGroup(string $groupId): array
	{
		return array_filter(
			$this->groupMembers,
			static fn(GroupMember $groupMember) => $groupMember->getGroupId() === $groupId,
		);
	}

	/**
	 * @inheritDoc
	 */
	public function findByUsers(array $userIds): array
	{
		return array_values(array_filter(
			$this->groupMembers,
			static fn(GroupMember $groupMember) => in_array($groupMember->getUserId(), $userIds, true),
		));
	}

	/**
	 * @inheritDoc
	 */
	public function findAll(): array
	{
		return array_values($this->groupMembers);
	}

	public function store(GroupMember $groupMember): string
	{
		$this->groupMembers[$groupMember->getGroupMemberId()] = $groupMember;
		return $groupMember->getGroupMemberId();
	}

	/**
	 * @inheritDoc
	 */
	public function delete(array $groupMembers): void
	{
		foreach ($groupMembers as $groupMember)
		{
			unset($this->groupMembers[$groupMember->getGroupMemberId()]);
		}
	}
}