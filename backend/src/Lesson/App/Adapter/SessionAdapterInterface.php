<?php
declare(strict_types=1);

namespace App\Lesson\App\Adapter;

interface SessionAdapterInterface
{
	public function getCurrentUserId(): string;
}