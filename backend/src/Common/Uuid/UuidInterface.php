<?php
declare(strict_types=1);

namespace App\Common\Uuid;

interface UuidInterface
{
	public function toString(): string;

	public function __toString(): string;

	public function toBytes(): string;

}