<?php
declare(strict_types=1);

namespace App\Subject\App\Query\Data;

use App\Common\Uuid\UuidInterface;

readonly class TeacherSubjectData
{
	public function __construct(
		public UuidInterface $teacherSubjectId,
		public UuidInterface $teacherId,
		public UuidInterface $subjectId,
	)
	{
	}
}