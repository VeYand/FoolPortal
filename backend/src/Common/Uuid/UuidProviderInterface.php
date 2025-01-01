<?php
declare(strict_types=1);

namespace App\Common\Uuid;

interface UuidProviderInterface
{
	public function generate(): string;

	public function toString(string $uuid): string;

	/**
	 * @param string[]|null $uuids
	 * @return string[]|null
	 */
	public function toStringList(?array $uuids): ?array;

	public function toBinary(?string $uuid): ?string;

	/**
	 * @param string[]|null $uuids
	 * @return string[]|null
	 */
	public function toBinaryList(?array $uuids): ?array;
}