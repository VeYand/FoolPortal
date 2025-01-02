<?php
declare(strict_types=1);

namespace App\Lesson\App\Adapter;

interface UserAdapterInterface
{
	/**
	 * @return string[]
	 */
	public function listUserGroupIds(string $userId): array;
}