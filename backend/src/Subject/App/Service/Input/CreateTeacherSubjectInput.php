<?php
declare(strict_types=1);

namespace App\Subject\App\Service\Input;

readonly class CreateTeacherSubjectInput
{
	public function __construct(
		public string $teacherId,
		public string $subjectId,
	)
	{
	}
}