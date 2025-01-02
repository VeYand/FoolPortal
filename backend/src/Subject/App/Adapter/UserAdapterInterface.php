<?php
declare(strict_types=1);

namespace App\Subject\App\Adapter;

use App\Common\Uuid\UuidInterface;
use App\Subject\App\Adapter\Data\UserData;

interface UserAdapterInterface
{
	public function getUser(UuidInterface $userId): ?UserData;

	public function isGroupExists(UuidInterface $groupId): bool;
}