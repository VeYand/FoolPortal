<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Model;

class Location
{
	public function __construct(
		private readonly string $locationId,
		private string          $name,
	)
	{
	}

	public function getLocationId(): string
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