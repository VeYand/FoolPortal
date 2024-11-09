<?php
declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\User\Domain\Model\GroupMember;

interface GroupMemberReadRepositoryInterface
{
	public function find(string $groupId, string $userId): ?GroupMember;
}