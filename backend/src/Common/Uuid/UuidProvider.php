<?php
declare(strict_types=1);

namespace App\Common\Uuid;

use Symfony\Component\Uid\Uuid;

class UuidProvider implements UuidProviderInterface
{
	public function generate(): string
	{
		return Uuid::v7()->toBinary();
	}

	public function toString(string $uuid): string
	{
		return Uuid::v7()::fromBinary($uuid)->toString();
	}

	public function toStringList(?array $uuids): ?array
	{
		if (is_null($uuids))
		{
			return null;
		}

		return array_map(
			static fn(string $uuid) => Uuid::fromBinary($uuid)->toString(),
			$uuids,
		);
	}

	public function toBinary(?string $uuid): ?string
	{
		if (is_null($uuid))
		{
			return null;
		}

		return Uuid::fromString($uuid)->toBinary();
	}

	/**
	 * @inheritDoc
	 */
	public function toBinaryList(?array $uuids): ?array
	{
		if (is_null($uuids))
		{
			return null;
		}

		return array_map(
			static fn(string $uuid) => Uuid::fromString($uuid)->toBinary(),
			$uuids,
		);
	}
}