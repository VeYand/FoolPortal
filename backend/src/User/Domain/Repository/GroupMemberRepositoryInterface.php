<?php
declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\Common\Uuid\UuidInterface;
use App\User\Domain\Model\GroupMember;

interface GroupMemberRepositoryInterface extends GroupMemberReadRepositoryInterface
{
	public function store(GroupMember $groupMember): UuidInterface;

	/**
	 * @param GroupMember[] $groupMembers
	 */
	public function delete(array $groupMembers): void;
}