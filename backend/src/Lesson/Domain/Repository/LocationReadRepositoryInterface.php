<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Repository;


use App\Common\Uuid\UuidInterface;
use App\Lesson\Domain\Model\Location;

interface LocationReadRepositoryInterface
{
	public function find(UuidInterface $locationId): ?Location;

	/**
	 * @param UuidInterface[] $locationIds
	 * @return Location[]
	 */
	public function findByIds(array $locationIds): array;

	/**
	 * @return Location[]
	 */
	public function findAll(): array;
}