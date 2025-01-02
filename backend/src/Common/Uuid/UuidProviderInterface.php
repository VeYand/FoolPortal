<?php
declare(strict_types=1);

namespace App\Common\Uuid;

interface UuidProviderInterface
{
	public function generate(): UuidInterface;

	public function fromBytesToUuid(?string $uuid): ?UuidInterface;

	public function fromStringToUuid(?string $uuid): ?UuidInterface;

	/**
	 * @param string[]|null $uuids
	 * @return UuidInterface[]|null
	 */
	public function fromStringsToUuids(?array $uuids): ?array;
}