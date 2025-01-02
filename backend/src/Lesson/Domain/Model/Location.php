<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Model;

use App\Common\Uuid\UuidInterface;

class Location
{
	public function __construct(
		private readonly UuidInterface $locationId,
		private string                 $name,
	)
	{
	}

	public function getLocationId(): UuidInterface
	{
		return $this->locationId;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}
}