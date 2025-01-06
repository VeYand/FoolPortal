<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Service;

use App\Common\Uuid\UuidInterface;
use App\Common\Uuid\UuidProviderInterface;
use App\Lesson\Domain\Exception\DomainException;
use App\Lesson\Domain\Model\Location;
use App\Lesson\Domain\Repository\LessonRepositoryInterface;
use App\Lesson\Domain\Repository\LocationRepositoryInterface;

readonly class LocationService
{
	public function __construct(
		private LocationRepositoryInterface $locationRepository,
		private LessonRepositoryInterface   $lessonRepository,
		private UuidProviderInterface       $uuidProvider,
	)
	{
	}

	/**
	 * @throws DomainException
	 */
	public function create(string $locationName): UuidInterface
	{
		$this->assertLocationNameIsUnique($locationName);
		$location = new Location(
			$this->uuidProvider->generate(),
			$locationName,
		);

		return $this->locationRepository->store($location);
	}

	/**
	 * @throws DomainException
	 */
	public function update(UuidInterface $locationId, string $locationName): void
	{
		$location = $this->locationRepository->find($locationId);

		if (is_null($location))
		{
			throw new DomainException('Location not found', DomainException::LOCATION_NOT_FOUND);
		}

		$this->assertLocationNameIsUnique($locationName);
		$location->setName($locationName);
		$this->locationRepository->store($location);
	}

	public function delete(UuidInterface $locationId): void
	{
		$location = $this->locationRepository->find($locationId);

		if (!is_null($location))
		{
			$this->removeLocationFromLessons($location->getLocationId());
			$this->locationRepository->delete($location);
		}
	}

	private function removeLocationFromLessons(UuidInterface $locationId): void
	{
		$lessons = $this->lessonRepository->findByLocation($locationId);

		foreach ($lessons as $lesson)
		{
			$lesson->setLocationId(null);
		}

		$this->lessonRepository->storeList($lessons);
	}

	/**
	 * @throws DomainException
	 */
	private function assertLocationNameIsUnique(string $name): void
	{
		$location = $this->locationRepository->findByName($name);

		if (!is_null($location))
		{
			throw new DomainException('Location with name "' . $name . '" already exists', DomainException::LOCATION_NAME_IS_NOT_UNIQUE);
		}
	}
}