<?php
declare(strict_types=1);

namespace App\Lesson\App\Query;

use App\Lesson\App\Query\Data\LocationData;
use App\Lesson\App\Query\Spec\ListLocationsSpec;

interface LocationQueryServiceInterface
{
	/**
	 * @return LocationData[]
	 */
	public function listLocations(ListLocationsSpec $spec): array;
}