<?php
declare(strict_types=1);

namespace App\Subject\App\Service\Input;

readonly class CreateCourseInput
{
	public function __construct(
		public string $groupId,
		public string $teacherSubjectId,
	)
	{
	}
}