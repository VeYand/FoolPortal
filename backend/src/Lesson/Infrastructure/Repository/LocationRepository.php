<?php
declare(strict_types=1);

namespace App\Lesson\Infrastructure\Repository;

use App\Common\Uuid\UuidInterface;
use App\Common\Uuid\UuidUtils;
use App\Lesson\Domain\Model\Location;
use App\Lesson\Domain\Repository\LocationRepositoryInterface;
use Doctrine\DBAL\ArrayParameterType;
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

	public function find(UuidInterface $locationId): ?Location
	{
		return $this->repository->find($locationId);
	}

	/**
	 * @inheritDoc
	 */
	public function findByIds(array $locationIds): array
	{
		$qb = $this->repository->createQueryBuilder('l');
		return $qb
			->where($qb->expr()->in('l.locationId', ':locationIds'))
			->setParameter('locationIds', UuidUtils::convertToBinaryList($locationIds), ArrayParameterType::STRING)
			->getQuery()
			->getResult();
	}

	public function findByName(string $locationName): ?Location
	{
		return $this->repository->findOneBy(['name' => $locationName]);
	}

	/**
	 * @inheritDoc
	 */
	public function findAll(): array
	{
		return $this->repository->findAll();
	}

	public function store(Location $location): UuidInterface
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