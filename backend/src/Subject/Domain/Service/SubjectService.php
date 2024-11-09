<?php
declare(strict_types=1);

namespace App\Subject\Domain\Service;

use App\Common\Exception\DomainException;
use App\Common\Uuid\UuidProviderInterface;
use App\Subject\Domain\Model\Subject;
use App\Subject\Domain\Repository\SubjectRepositoryInterface;

readonly class SubjectService
{
	public function __construct(
		private SubjectRepositoryInterface $subjectRepository,
		private UuidProviderInterface      $uuidProvider,
	)
	{
	}

	public function create(string $subjectName): string
	{
		$subject = new Subject(
			$this->uuidProvider->generate(),
			$subjectName,
		);

		return $this->subjectRepository->store($subject);
	}

	/**
	 * @throws DomainException
	 */
	public function update(string $subjectId, string $subjectName): void
	{
		$subject = $this->subjectRepository->find($subjectId);

		if (is_null($subject))
		{
			throw new DomainException('Subject not found', 404);
		}

		$subject->setName($subjectName);
		$this->subjectRepository->store($subject);
	}
}