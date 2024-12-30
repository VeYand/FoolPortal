<?php
declare(strict_types=1);

namespace App\Lesson\App\Query\Spec;

readonly class ListLocationsSpec
{
	/**
	 * @param string[]|null $locationIds
	 */
	public function __construct(
		public ?array $locationIds
	)
	{
	}
}