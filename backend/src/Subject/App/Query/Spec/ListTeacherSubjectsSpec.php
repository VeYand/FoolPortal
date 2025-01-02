<?php
declare (strict_types=1);

namespace App\Subject\App\Query\Spec;

use App\Common\Uuid\UuidInterface;

readonly class ListTeacherSubjectsSpec
{
	/**
	 * @param UuidInterface[]|null $courseIds
	 */
	public function __construct(
		public ?array $courseIds = null,
	)
	{
	}
}