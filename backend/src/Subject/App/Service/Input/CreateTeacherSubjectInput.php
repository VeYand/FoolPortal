<?php
declare(strict_types=1);

namespace App\Subject\App\Service\Input;

use App\Common\Uuid\UuidInterface;

readonly class CreateTeacherSubjectInput
{
	public function __construct(
		public UuidInterface $teacherId,
		public UuidInterface $subjectId,
	)
	{
	}
}