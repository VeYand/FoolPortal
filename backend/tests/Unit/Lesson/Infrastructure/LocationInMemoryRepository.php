<?php
declare(strict_types=1);

namespace App\Tests\Unit\Lesson\Infrastructure;

use App\Lesson\Domain\Model\Location;
use App\Lesson\Domain\Repository\LocationRepositoryInterface;

class LocationInMemoryRepository implements LocationRepositoryInterface
{
	/** @var array<string, Location> */
	private array $locations = [];

	public function find(string $locationId): ?Location
	{
		return $this->locations[$locationId] ?? null;
	}

	/**
	 * @inheritDoc
	 */
	public function findByIds(array $locationIds): array
	{
		return array_filter(
			$this->locations,
			static fn(Location $location) => in_array($location->getLocationId(), $locationIds, true),
		);
	}

	public function store(Location $location): string
	{
		$this->locations[$location->getLocationId()] = $location;
		return $location->getLocationId();
	}

	public function delete(Location $location): void
	{
		unset($this->locations[$location->getLocationId()]);
	}
}
