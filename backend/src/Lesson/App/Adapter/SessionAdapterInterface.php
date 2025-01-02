<?php
declare(strict_types=1);

namespace App\Lesson\App\Adapter;

use App\Common\Uuid\UuidInterface;

interface SessionAdapterInterface
{
	public function getCurrentUserId(): UuidInterface;
}