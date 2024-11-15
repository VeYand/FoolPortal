<?php
declare(strict_types=1);

namespace App\Subject\App\Adapter;

use App\Subject\App\Adapter\Data\UserData;

interface UserAdapterInterface
{
	public function getUser(string $userId): ?UserData;

	public function isGroupExists(string $groupId): bool;
}