<?php
declare(strict_types=1);

namespace App\Common\Uuid;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface as RamseyUuidInterface;

readonly class RamseyUuid implements UuidInterface
{
	public function __construct(
		private RamseyUuidInterface $uuid,
	)
	{
	}

	public function toString(): string
	{
		return $this->uuid->toString();
	}

	public static function fromBytes(string $uuid): self
	{
		return new self(Uuid::fromBytes($uuid));
	}

	public static function fromString(string $uuid): self
	{
		return new self(Uuid::fromString($uuid));
	}

	public function __toString(): string
	{
		return $this->toString();
	}

	public function toBytes(): string
	{
		return $this->uuid->getBytes();
	}
}