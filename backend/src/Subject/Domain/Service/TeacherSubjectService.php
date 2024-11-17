<?php
declare(strict_types=1);

namespace App\Subject\Domain\Service;

use App\Common\Uuid\UuidProviderInterface;
use App\Subject\Domain\Exception\DomainException;
use App\Subject\Domain\Model\TeacherSubject;
use App\Subject\Domain\Repository\CourseRepositoryInterface;
use App\Subject\Domain\Repository\SubjectReadRepositoryInterface;
use App\Subject\Domain\Repository\TeacherSubjectRepositoryInterface;

readonly class TeacherSubjectService
{
	public function __construct(
		private TeacherSubjectRepositoryInterface $teacherSubjectRepository,
		private CourseRepositoryInterface         $courseRepository,
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

	public function deleteByTeacher(string $teacherId): void // TODO оптимизировать
	{
		$teacherSubjects = $this->teacherSubjectRepository->findByTeacher($teacherId);

		foreach ($teacherSubjects as $teacherSubject)
		{
			$this->delete($teacherSubject->getTeacherSubjectId());
		}
	}

	public function delete(string $teacherSubjectId): void
	{
		$teacherSubject = $this->teacherSubjectRepository->find($teacherSubjectId);
		if (!is_null($teacherSubject))
		{
			$courses = $this->courseRepository->findByTeacherSubjects([$teacherSubject->getTeacherSubjectId()]);
			$this->courseRepository->delete($courses);
			$this->teacherSubjectRepository->delete([$teacherSubject]);
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
			throw new DomainException('Teacher subject already exists', DomainException::TEACHER_SUBJECT_ALREADY_EXISTS);
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
			throw new DomainException('Subject not found', DomainException::SUBJECT_NOT_FOUND);
		}
	}
}