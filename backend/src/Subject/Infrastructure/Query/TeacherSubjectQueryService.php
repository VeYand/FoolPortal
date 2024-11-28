<?php
declare(strict_types=1);

namespace App\Subject\Infrastructure\Query;

use App\Subject\App\Query\Data\TeacherSubjectData;
use App\Subject\App\Query\TeacherSubjectQueryServiceInterface;
use App\Subject\Domain\Model\TeacherSubject;
use App\Subject\Domain\Repository\TeacherSubjectReadRepositoryInterface;

readonly class TeacherSubjectQueryService implements TeacherSubjectQueryServiceInterface
{
	public function __construct(
		private TeacherSubjectReadRepositoryInterface $teacherSubjectReadRepository,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function listAllTeacherSubjects(): array
	{
		$teacherSubjects = $this->teacherSubjectReadRepository->findAll();
		return self::convertTeacherSubjectsToTeacherSubjectList($teacherSubjects);
	}

	/**
	 * @inheritDoc
	 */
	public function listTeacherSubjectsByGroup(string $groupId): array
	{
		$teacherSubjects = $this->teacherSubjectReadRepository->findByGroup($groupId);
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