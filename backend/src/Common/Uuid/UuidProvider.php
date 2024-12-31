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

	/**
	 * @inheritDoc
	 */
	public function toBinaryList(array $uuids): array
	{
		return array_map(
			static fn(string $uuid) => Uuid::fromString($uuid)->toBinary(),
			$uuids,
		);
	}
}