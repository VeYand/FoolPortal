<?php
declare (strict_types=1);

namespace App\Subject\App\Query\Spec;

readonly class ListCoursesSpec
{
	/**
	 * @param string[]|null $courseIds
	 * @param string[]|null $groupIds
	 */
	public function __construct(
		public ?array $courseIds = null,
		public ?array $groupIds = null,
	)
	{
	}
}