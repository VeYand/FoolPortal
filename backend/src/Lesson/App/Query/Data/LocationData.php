<?php
declare(strict_types=1);

namespace App\Lesson\App\Query\Data;

use App\Common\Uuid\UuidInterface;

readonly class LocationData
{
	public function __construct(
		public UuidInterface $locationId,
		public string        $name,
	)
	{
	}
}