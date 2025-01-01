<?php
declare(strict_types=1);

namespace App\Subject\Infrastructure\Query;

use App\Subject\App\Query\Data\TeacherSubjectData;
use App\Subject\App\Query\Spec\ListTeacherSubjectsSpec;
use App\Subject\App\Query\TeacherSubjectQueryServiceInterface;
use App\Subject\Domain\Model\Course;
use App\Subject\Domain\Model\TeacherSubject;
use Doctrine\ORM\EntityManagerInterface;

readonly class TeacherSubjectQueryService implements TeacherSubjectQueryServiceInterface
{
	public function __construct(
		private EntityManagerInterface $entityManager,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function listTeacherSubjects(ListTeacherSubjectsSpec $spec): array
	{
		$qb = $this->entityManager->createQueryBuilder();

		$qb->select('ts')
			->from(TeacherSubject::class, 'ts')
			->leftJoin(Course::class, 'c', 'WITH', 'c.teacherSubjectId = ts.teacherSubjectId');

		if (!empty($spec->courseIds))
		{
			$qb->andWhere('c.courseId IN (:courseIds)')
				->setParameter('courseIds', $spec->courseIds);
		}

		$teacherSubjects = $qb->getQuery()->getResult();
		return self::convertTeacherSubjectsToTeacherSubjectList($teacherSubjects);
	}

	/**
	 * @param TeacherSubject[] $teacherSubjects
	 * @return TeacherSubjectData[]
	 */
	public static function convertTeacherSubjectsToTeacherSubjectList(array $teacherSubjects): array
	{
		return array_map(static fn(TeacherSubject $teacherSubject) => new TeacherSubjectData(
			$teacherSubject->getTeacherSubjectId(),
			$teacherSubject->getTeacherId(),
			$teacherSubject->getSubjectId(),
		), $teacherSubjects);
	}
}