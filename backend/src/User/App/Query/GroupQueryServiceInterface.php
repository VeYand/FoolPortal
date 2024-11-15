<?php
declare(strict_types=1);

namespace App\User\App\Query;

use App\User\App\Query\Data\GroupData;

interface GroupQueryServiceInterface
{
	public function isGroupExists(string $groupId): bool;

	/**
	 * @return GroupData[]
	 */
	public function listAllGroups(): array;
}