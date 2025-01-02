<?php
declare(strict_types=1);

namespace App\Lesson\App\Adapter;

use App\Common\Uuid\UuidInterface;
use App\Lesson\App\Adapter\Data\UserRole;

interface SessionAdapterInterface
{
	public function getCurrentUserId(): UuidInterface;

	public function getCurrentUserRole(): UserRole;
}