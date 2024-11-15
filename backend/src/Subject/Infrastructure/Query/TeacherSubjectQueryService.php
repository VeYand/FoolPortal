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
		$subjects = $this->teacherSubjectReadRepository->findAll();

		return array_map(static fn(TeacherSubject $subject) => new TeacherSubjectData(
			$subject->getTeacherSubjectId(),
			$subject->getTeacherId(),
			$subject->getSubjectId(),
		), $subjects);
	}
}