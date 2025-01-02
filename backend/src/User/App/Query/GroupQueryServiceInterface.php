<?php
declare(strict_types=1);

namespace App\User\App\Query;

use App\Common\Uuid\UuidInterface;
use App\User\App\Query\Data\GroupData;
use App\User\App\Query\Spec\ListGroupsSpec;

interface GroupQueryServiceInterface
{
	public function isGroupExists(UuidInterface $groupId): bool;

	/**
	 * @return GroupData[]
	 */
	public function listGroups(ListGroupsSpec $spec): array;
}