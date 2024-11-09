<?php
declare(strict_types=1);

namespace App\Lesson\Infrastructure\Repository;

use App\Lesson\Domain\Model\Location;
use App\Lesson\Domain\Repository\LocationRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class LocationRepository implements LocationRepositoryInterface
{
	private EntityRepository $repository;

	public function __construct(
		private readonly EntityManagerInterface $entityManager,
	)
	{
		$this->repository = $this->entityManager->getRepository(Location::class);
	}

	public function find(string $locationId): ?Location
	{
		return $this->repository->find($locationId);
	}

	public function store(Location $location): string
	{
		$this->entityManager->persist($location);
		$this->entityManager->flush();
		return $location->getLocationId();
	}

	public function delete(Location $location): void
	{
		$this->entityManager->remove($location);
		$this->entityManager->flush();
	}
}