<?php
declare(strict_types=1);

namespace App\Common\Uuid;

use Ramsey\Uuid\Uuid;

class UuidProvider implements UuidProviderInterface
{
	public function generate(): UuidInterface
	{
		return new RamseyUuid(Uuid::uuid7());
	}

	public function fromBytesToUuid(?string $uuid): ?UuidInterface
	{
		if (is_null($uuid))
		{
			return null;
		}

		return new RamseyUuid(Uuid::fromBytes($uuid));
	}

	public
	function fromStringToUuid(
		?string $uuid,
	): ?UuidInterface
	{
		if (is_null($uuid))
		{
			return null;
		}

		return new RamseyUuid(Uuid::fromString($uuid));
	}

	/**
	 * @inheritDoc
	 */
	public
	function fromStringsToUuids(
		?array $uuids,
	): ?array
	{
		if (is_null($uuids))
		{
			return null;
		}

		return array_map(
			fn(string $uuid) => $this->fromStringToUuid($uuid),
			array_values($uuids),
		);
	}
}