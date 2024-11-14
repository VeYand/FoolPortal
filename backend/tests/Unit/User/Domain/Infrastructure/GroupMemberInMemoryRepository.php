<?php
declare(strict_types=1);

namespace App\Tests\Unit\User\Domain\Infrastructure;

use App\User\Domain\Model\GroupMember;
use App\User\Domain\Repository\GroupMemberRepositoryInterface;

class GroupMemberInMemoryRepository implements GroupMemberRepositoryInterface
{
	public function find(string $groupId, string $userId): ?GroupMember
	{
		// TODO: Implement find() method.
		return null;
	}

	/**
	 * @inheritDoc
	 */
	public function findByGroup(string $groupId): array
	{
		// TODO: Implement findByGroup() method.
		return [];
	}

	/**
	 * @inheritDoc
	 */
	public function findByUser(string $userId): array
	{
		// TODO: Implement findByUser() method.
		return [];
	}

	/**
	 * @inheritDoc
	 */
	public function findAll(): array
	{
		// TODO: Implement findAll() method.
		return [];
	}

	public function store(GroupMember $groupMember): string
	{
		// TODO: Implement store() method.
		return '';
	}

	/**
	 * @inheritDoc
	 */
	public function delete(array $groupMembers): void
	{
		// TODO: Implement delete() method.
	}
}