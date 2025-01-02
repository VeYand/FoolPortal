<?php
declare(strict_types=1);

namespace App\Lesson\Infrastructure\Adapter;

use App\Lesson\App\Adapter\SubjectAdapterInterface;
use App\Subject\Api\SubjectApiInterface;
use App\Subject\App\Query\Data\CourseData;
use App\Subject\App\Query\Spec\ListCoursesSpec;

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

	/**
	 * @inheritDoc
	 */
	public function listCourseIdsByGroupIds(array $groupIds): array
	{
		$courses = $this->subjectApi->listCourses(new ListCoursesSpec(groupIds: $groupIds));
		return array_map(static fn(CourseData $course) => $course->courseId, $courses);

	}
}