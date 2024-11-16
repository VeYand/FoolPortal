<?php
declare(strict_types=1);

namespace App\Lesson\App\Query\Data;

readonly class LocationData
{
	public function __construct(
		public string $locationId,
		public string $name,
	)
	{
	}
}