<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Repository;


use App\Lesson\Domain\Model\Location;

interface LocationReadRepositoryInterface
{
	public function find(string $locationId): ?Location;
}