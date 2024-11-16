<?php
declare(strict_types=1);

namespace App\Lesson\Infrastructure\Adapter;

use App\Lesson\App\Adapter\SubjectAdapterInterface;
use App\Subject\Api\SubjectApiInterface;

readonly class SubjectAdapter implements SubjectAdapterInterface
{
	public function __construct(
		private SubjectApiInterface $subjectApi,
	)
	{
	}

	public function isCourseExists(string $courseId): bool
	{
		return $this->subjectApi->isCourseExists($courseId);
	}
}