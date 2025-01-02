<?php
declare(strict_types=1);

namespace App\Subject\Infrastructure\Query;

use App\Common\Uuid\UuidInterface;
use App\Common\Uuid\UuidUtils;
use App\Subject\App\Query\CourseQueryServiceInterface;
use App\Subject\App\Query\Data\CourseData;
use App\Subject\App\Query\Spec\ListCoursesSpec;
use App\Subject\Domain\Model\Course;
use App\Subject\Domain\Model\TeacherSubject;
use App\Subject\Domain\Repository\CourseReadRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

readonly class CourseQueryService implements CourseQueryServiceInterface
{
	public function __construct(
		private CourseReadRepositoryInterface $courseReadRepository,
		private EntityManagerInterface        $entityManager,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function listCourses(ListCoursesSpec $spec): array
	{
		$qb = $this->entityManager->createQueryBuilder();

		$qb->select('c')
			->from(Course::class, 'c')
			->leftJoin(TeacherSubject::class, 'ts', 'WITH', 'ts.teacherSubjectId = c.teacherSubjectId');

		if (!empty($spec->courseIds))
		{
			$qb->andWhere('c.courseId IN (:courseIds)')
				->setParameter('courseIds', UuidUtils::convertToBinaryList($spec->courseIds));
		}

		if (!empty($spec->groupIds))
		{
			$qb->andWhere('c.groupId IN (:groupIds)')
				->setParameter('groupIds', UuidUtils::convertToBinaryList($spec->groupIds));
		}

		if (!empty($spec->teacherSubjectIds))
		{
			$qb->andWhere('ts.teacherSubjectId IN (:teacherSubjectIds)')
				->setParameter('teacherSubjectIds', UuidUtils::convertToBinaryList($spec->teacherSubjectIds));
		}

		$courses = $qb->getQuery()->getResult();
		return self::convertCoursesToCoursesList($courses);
	}

	public function isCourseExists(UuidInterface $courseId): bool
	{
		$course = $this->courseReadRepository->find($courseId);

		return !is_null($course);
	}

	/**
	 * @param Course[] $courses
	 * @return CourseData[]
	 */
	private static function convertCoursesToCoursesList(array $courses): array
	{
		return array_map(
			static fn(Course $course) => new CourseData(
				$course->getCourseId(),
				$course->getTeacherSubjectId(),
				$course->getGroupId(),
			),
			$courses,
		);
	}
}