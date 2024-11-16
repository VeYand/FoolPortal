<?php
declare(strict_types=1);

namespace App\Lesson\App\Query;

use App\Lesson\App\Query\Data\LocationData;

interface LocationQueryServiceInterface
{
	/**
	 * @param string[] $locationIds
	 * @return LocationData[]
	 */
	public function findLocationsByIds(array $locationIds): array;
}