<?php
declare(strict_types=1);

namespace App\Lesson\App\Query\Spec;

use App\Common\Uuid\UuidInterface;

readonly class ListLocationsSpec
{
	/**
	 * @param UuidInterface[]|null $locationIds
	 */
	public function __construct(
		public ?array $locationIds = null,
	)
	{
	}
}