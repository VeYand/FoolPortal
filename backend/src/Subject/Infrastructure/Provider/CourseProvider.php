<?php
declare(strict_types=1);

namespace App\Subject\Infrastructure\Provider;

use App\Subject\Domain\Model\Course;
use App\Subject\Domain\Provider\CourseProviderInterface;
use App\Subject\Domain\Repository\CourseReadRepositoryInterface;

readonly class CourseProvider implements CourseProviderInterface
{
	public function __construct(
		private CourseReadRepositoryInterface $courseReadRepository,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function findCourseIdsByGroupIds(array $groupIds): array
	{
		$courses = $this->courseReadRepository->findByGroups($groupIds);

		return array_map(
			static fn(Course $course) => $course->getCourseId(),
			$courses,
		);
	}
}