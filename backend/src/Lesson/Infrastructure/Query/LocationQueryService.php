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

		return array_map(static fn(Location $location) => new LocationData(
			$location->getLocationId(),
			$location->getName(),
		), $locations);
	}
}