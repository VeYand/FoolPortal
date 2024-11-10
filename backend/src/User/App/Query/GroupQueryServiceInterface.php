<?php
declare(strict_types=1);

namespace App\User\App\Query;

use App\User\App\Query\Data\GroupData;

interface GroupQueryServiceInterface
{
	/**
	 * @return GroupData[]
	 */
	public function listAllGroups(): array;
}