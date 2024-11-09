<?php
declare(strict_types=1);

namespace App\Subject\Domain\Service;

use App\Common\Exception\DomainException;
use App\Common\Uuid\UuidProviderInterface;
use App\Subject\Domain\Model\TeacherSubject;
use App\Subject\Domain\Repository\SubjectReadRepositoryInterface;
use App\Subject\Domain\Repository\TeacherSubjectRepositoryInterface;

readonly class TeacherSubjectRepository
{
	public function __construct(
		private TeacherSubjectRepositoryInterface $teacherSubjectRepository,
		private SubjectReadRepositoryInterface    $subjectReadRepository,
		private UuidProviderInterface             $uuidProvider,
	)
	{
	}

	/**
	 * @throws DomainException
	 */
	public function create(string $teacherId, string $subjectId): void
	{
		$this->assertSubjectExists($subjectId);
		$this->assertTeacherSubjectNotExists($teacherId, $subjectId);

		$teacherSubject = new TeacherSubject(
			$this->uuidProvider->generate(),
			$teacherId,
			$subjectId,
		);

		$this->teacherSubjectRepository->store($teacherSubject);
	}

	public function delete(string $teacherSubjectId): void
	{
		$teacherSubject = $this->teacherSubjectRepository->find($teacherSubjectId);
		if (!is_null($teacherSubject))
		{
			$this->teacherSubjectRepository->delete($teacherSubject);
		}
	}

	/**
	 * @throws DomainException
	 */
	private function assertTeacherSubjectNotExists(string $teacherId, string $subjectId): void
	{
		$teacherSubject = $this->teacherSubjectRepository->findByTeacherAndSubject($teacherId, $subjectId);

		if (!is_null($teacherSubject))
		{
			throw new DomainException('Teacher subject already exists', 409);
		}
	}

	/**
	 * @throws DomainException
	 */
	private function assertSubjectExists(string $subjectId): void
	{
		$subject = $this->subjectReadRepository->find($subjectId);

		if (is_null($subject))
		{
			throw new DomainException('Subject not found', 404);
		}
	}
}