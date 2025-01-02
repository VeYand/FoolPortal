<?php
declare(strict_types=1);

namespace App\Subject\App\Service\Input;

use App\Common\Uuid\UuidInterface;

readonly class CreateCourseInput
{
	public function __construct(
		public UuidInterface $groupId,
		public UuidInterface $teacherSubjectId,
	)
	{
	}
}