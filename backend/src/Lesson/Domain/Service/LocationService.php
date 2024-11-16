<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Service;

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
			throw new DomainException('Location not found', DomainException::LOCATION_NOT_FOUND);
		}

		$location->setName($locationName);
		$this->locationRepository->store($location);
	}

	public function delete(string $locationId): void
	{
		$location = $this->locationRepository->find($locationId);

		if (!is_null($location))
		{
			$this->removeLocationFromLessons($location->getLocationId());
			$this->locationRepository->delete($location);
		}
	}

	private function removeLocationFromLessons(string $locationId): void
	{
		$lessons = $this->lessonRepository->findByLocation($locationId);

		foreach ($lessons as $lesson)
		{
			$lesson->setLocationId(null);
		}

		$this->lessonRepository->storeList($lessons);
	}
}