<?php
declare(strict_types=1);

namespace App\Lesson\App\Adapter;

use App\Common\Uuid\UuidInterface;

interface UserAdapterInterface
{
	/**
	 * @return UuidInterface[]
	 */
	public function listUserGroupIds(UuidInterface $userId): array;
}