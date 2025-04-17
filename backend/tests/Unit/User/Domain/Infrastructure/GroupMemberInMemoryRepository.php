<?php
declare(strict_types=1);

namespace App\Tests\Unit\User\Domain\Infrastructure;

use App\Common\Uuid\UuidInterface;
use App\User\Domain\Model\GroupMember;
use App\User\Domain\Repository\GroupMemberRepositoryInterface;

class GroupMemberInMemoryRepository implements GroupMemberRepositoryInterface
{
	/** @var array<string, GroupMember> */
	private array $groupMembers = [];

	public function find(UuidInterface $groupId, UuidInterface $userId): ?GroupMember
	{
		foreach ($this->groupMembers as $groupMember)
		{
			if ($groupId->toString() === $groupMember->getGroupId()->toString()
				&& $userId->toString() === $groupMember->getUserId()->toString())
			{
				return $groupMember;
			}
		}
		return null;
	}

	/**
	 * @inheritDoc
	 */
	public function findByGroup(UuidInterface $groupId): array
	{
		return array_filter($this->groupMembers, static function (GroupMember $groupMember) use ($groupId)
		{
			return $groupId->toString() === $groupMember->getGroupId()->toString();
		});
	}

	/**
	 * @inheritDoc
	 */
	public function findByUsers(array $userIds): array
	{
		$ids = array_map(static function (UuidInterface $userId)
		{
			return $userId->toString();
		}, $userIds);

		return array_filter($this->groupMembers, static function (GroupMember $groupMember) use ($ids)
		{
			return in_array($groupMember->getUserId()->toString(), $ids, true);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function findAll(): array
	{
		return array_values($this->groupMembers);
	}

	public function store(GroupMember $groupMember): UuidInterface
	{
		$this->groupMembers[$groupMember->getGroupMemberId()->toString()] = $groupMember;
		return $groupMember->getGroupMemberId();
	}

	/**
	 * @inheritDoc
	 */
	public function delete(array $groupMembers): void
	{
		$groupMembersIds = array_map(static function (GroupMember $groupMember)
		{
			return $groupMember->getGroupMemberId()->toString();
		}, $groupMembers);

		foreach ($groupMembersIds as $groupMemberId)
		{
			unset($this->groupMembers[$groupMemberId]);
		}
	}
}