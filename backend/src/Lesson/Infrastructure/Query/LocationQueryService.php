<?php
declare(strict_types=1);

namespace App\Lesson\Infrastructure\Query;

use App\Lesson\App\Query\Data\LocationData;
use App\Lesson\App\Query\LocationQueryServiceInterface;
use App\Lesson\Domain\Model\Location;
use App\Lesson\Domain\Repository\LocationReadRepositoryInterface;

readonly class LocationQueryService implements LocationQueryServiceInterface
{
	public function __construct(
		private LocationReadRepositoryInterface $locationReadRepository,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function findLocationsByIds(array $locationIds): array
	{
		$locations = $this->locationReadRepository->findByIds($locationIds);

		return self::convertLocationsToLocationsList($locations);
	}

	/**
	 * @inheritDoc
	 */
	public function listAllLocations(): array
	{
		$locations = $this->locationReadRepository->findAll();

		return self::convertLocationsToLocationsList($locations);
	}

	private static function convertLocationToLocationData(Location $location): LocationData
	{
		return new LocationData(
			$location->getLocationId(),
			$location->getName(),
		);
	}

	/**
	 * @param Location[] $locations
	 * @return LocationData[]
	 */
	private static function convertLocationsToLocationsList(array $locations): array
	{
		return array_map(
			static fn(Location $location) => self::convertLocationToLocationData($location),
			$locations,
		);
	}
}