<?php
declare(strict_types=1);

namespace App\Subject\App\Query\Data;

readonly class TeacherSubjectData
{
	public function __construct(
		public string $teacherSubjectId,
		public string $teacherId,
		public string $subjectId,
	)
	{
	}
}