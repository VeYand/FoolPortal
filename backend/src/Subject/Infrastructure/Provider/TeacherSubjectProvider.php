<?php
declare(strict_types=1);

namespace App\Subject\Infrastructure\Provider;

use App\Subject\Domain\Model\TeacherSubject;
use App\Subject\Domain\Provider\TeacherSubjectProviderInterface;
use App\Subject\Domain\Repository\TeacherSubjectReadRepositoryInterface;

readonly class TeacherSubjectProvider implements TeacherSubjectProviderInterface
{
	public function __construct(
		private TeacherSubjectReadRepositoryInterface $teacherSubjectReadRepository,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function findTeacherSubjectIdsByTeacherIds(array $teacherIds): array
	{
		$teacherSubjects = $this->teacherSubjectReadRepository->findByTeachers($teacherIds);

		return array_map(
			static fn(TeacherSubject $teacherSubject) => $teacherSubject->getTeacherSubjectId(),
			$teacherSubjects,
		);
	}
}