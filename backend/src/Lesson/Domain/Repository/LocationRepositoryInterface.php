<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Repository;


use App\Common\Uuid\UuidInterface;
use App\Lesson\Domain\Model\Location;

interface LocationRepositoryInterface extends LocationReadRepositoryInterface
{
	public function store(Location $location): UuidInterface;

	public function delete(Location $location): void;
}