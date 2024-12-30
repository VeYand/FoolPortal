<?php
declare (strict_types=1);

namespace App\Subject\App\Query\Spec;

readonly class ListTeacherSubjectsSpec
{
	/**
	 * @param string[]|null $courseIds
	 */
	public function __construct(
		public ?array $courseIds = null,
	)
	{
	}
}