<?php
declare(strict_types=1);

namespace App\Subject\Infrastructure\Query;

use App\Subject\App\Query\CourseQueryServiceInterface;
use App\Subject\App\Query\Data\CourseData;
use App\Subject\Domain\Model\Course;
use App\Subject\Domain\Repository\CourseReadRepositoryInterface;

readonly class CourseQueryService implements CourseQueryServiceInterface
{
	public function __construct(
		private CourseReadRepositoryInterface $courseReadRepository,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function listAllCourses(): array
	{
		$courses = $this->courseReadRepository->findAll();

		return array_map(static fn(Course $course) => new CourseData(
			$course->getCourseId(),
			$course->getTeacherSubjectId(),
			$course->getGroupId(),
		), $courses);
	}
}