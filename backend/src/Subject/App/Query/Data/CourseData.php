<?php
declare(strict_types=1);

namespace App\Subject\App\Query\Data;

readonly class CourseData
{
	public function __construct(
		public string $courseId,
		public string $teacherSubjectId,
		public string $groupId,
	)
	{
	}
}