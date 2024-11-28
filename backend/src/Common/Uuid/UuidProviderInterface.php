<?php
declare(strict_types=1);

namespace App\Common\Uuid;

interface UuidProviderInterface
{
	public function generate(): string;

	public function toString(string $uuid): string;
}