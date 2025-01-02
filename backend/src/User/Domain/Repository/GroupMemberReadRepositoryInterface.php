<?php
declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\Common\Uuid\UuidInterface;
use App\User\Domain\Model\GroupMember;

interface GroupMemberReadRepositoryInterface
{
	public function find(UuidInterface $groupId, UuidInterface $userId): ?GroupMember;

	/**
	 * @return GroupMember[]
	 */
	public function findByGroup(UuidInterface $groupId): array;

	/**
	 * @param UuidInterface[] $userIds
	 * @return GroupMember[]
	 */
	public function findByUsers(array $userIds): array;

	/**
	 * @return GroupMember[]
	 */
	public function findAll(): array;
}