<?php
declare(strict_types=1);

namespace App\Subject\Infrastructure\Query;

use App\Subject\App\Query\Data\SubjectData;
use App\Subject\App\Query\SubjectQueryServiceInterface;
use App\Subject\Domain\Model\Subject;
use App\Subject\Domain\Repository\SubjectReadRepositoryInterface;

readonly class SubjectQueryService implements SubjectQueryServiceInterface
{
	public function __construct(
		private SubjectReadRepositoryInterface $subjectReadRepository,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function listAllSubjects(): array
	{
		$subjects = $this->subjectReadRepository->findAll();

		return array_map(static fn(Subject $subject) => new SubjectData(
			$subject->getSubjectId(),
			$subject->getName(),
		), $subjects);
	}
}