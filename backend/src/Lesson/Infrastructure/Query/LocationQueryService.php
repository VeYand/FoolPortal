<?php
declare(strict_types=1);

namespace App\Lesson\Infrastructure\Query;

use App\Common\Uuid\UuidUtils;
use App\Lesson\App\Query\Data\LocationData;
use App\Lesson\App\Query\LocationQueryServiceInterface;
use App\Lesson\App\Query\Spec\ListLocationsSpec;
use App\Lesson\Domain\Model\Location;
use Doctrine\ORM\EntityManagerInterface;

readonly class LocationQueryService implements LocationQueryServiceInterface
{
	public function __construct(
		private EntityManagerInterface $entityManager,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function listLocations(ListLocationsSpec $spec): array
	{
		$qb = $this->entityManager->createQueryBuilder();

		$qb->select('l')
			->from(Location::class, 'l');

		if (!empty($spec->locationIds))
		{
			$qb->andWhere('l.locationId IN (:locationIds)')
				->setParameter('locationIds', UuidUtils::convertToBinaryList($spec->locationIds));
		}

		$locations = $qb->getQuery()->getResult();
		return self::convertLocationsToLocationsList($locations);
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

	private static function convertLocationToLocationData(Location $location): LocationData
	{
		return new LocationData(
			$location->getLocationId(),
			$location->getName(),
		);
	}
}