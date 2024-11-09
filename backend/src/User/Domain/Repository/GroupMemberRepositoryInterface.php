<?php
declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\User\Domain\Model\GroupMember;

interface GroupMemberRepositoryInterface extends GroupMemberReadRepositoryInterface
{
	public function store(GroupMember $groupMember): string;

	/**
	 * @param GroupMember[] $groupMembers
	 */
	public function delete(array $groupMembers): void;
}