<?php
declare(strict_types=1);

namespace App\User\App\Query;

use App\User\App\Query\Data\GroupData;
use App\User\App\Query\Spec\ListGroupsSpec;

interface GroupQueryServiceInterface
{
	public function isGroupExists(string $groupId): bool;

	/**
	 * @return GroupData[]
	 */
	public function listGroups(ListGroupsSpec $spec): array;
}