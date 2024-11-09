<?php
declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\User\Domain\Model\Group;

interface GroupReadRepositoryInterface
{
	public function find(string $groupId): ?Group;
}