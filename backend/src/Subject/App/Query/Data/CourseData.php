<?php
declare(strict_types=1);

namespace App\Subject\App\Query\Data;

use App\Common\Uuid\UuidInterface;

readonly class CourseData
{
	public function __construct(
		public UuidInterface $courseId,
		public UuidInterface $teacherSubjectId,
		public UuidInterface $groupId,
	)
	{
	}
}