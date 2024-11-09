<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Repository;


use App\Lesson\Domain\Model\Location;

interface LocationRepositoryInterface extends LocationReadRepositoryInterface
{
	public function store(Location $location): string;

	public function delete(Location $location): void;
}