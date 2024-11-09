<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Service;

use App\Common\Exception\DomainException;
use App\Common\Uuid\UuidProviderInterface;
use App\Lesson\Domain\Model\Location;
use App\Lesson\Domain\Repository\LocationRepositoryInterface;

readonly class LocationService
{
	public function __construct(
		private LocationRepositoryInterface $locationRepository,
		private UuidProviderInterface       $uuidProvider,
	)
	{
	}

	public function create(string $locationName): string
	{
		$location = new Location(
			$this->uuidProvider->generate(),
			$locationName,
		);

		return $this->locationRepository->store($location);
	}

	/**
	 * @throws DomainException
	 */
	public function update(string $locationId, string $locationName): void
	{
		$location = $this->locationRepository->find($locationId);

		if (is_null($location))
		{
			throw new DomainException('Location not found', 404);
		}

		$location->setName($locationName);
		$this->locationRepository->store($location);
	}
}