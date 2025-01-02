<?php
declare (strict_types=1);

namespace App\Subject\App\Query\Spec;

use App\Common\Uuid\UuidInterface;

readonly class ListCoursesSpec
{
	/**
	 * @param UuidInterface[]|null $courseIds
	 * @param UuidInterface[]|null $groupIds
	 */
	public function __construct(
		public ?array $courseIds = null,
		public ?array $groupIds = null,
	)
	{
	}
}