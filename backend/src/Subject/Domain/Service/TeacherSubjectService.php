<?php
declare(strict_types=1);

namespace App\Subject\Domain\Service;

use App\Common\Event\EventPublisherInterface;
use App\Common\Uuid\UuidInterface;
use App\Common\Uuid\UuidProviderInterface;
use App\Subject\Domain\Exception\DomainException;
use App\Subject\Domain\Model\Course;
use App\Subject\Domain\Model\TeacherSubject;
use App\Subject\Domain\Repository\CourseRepositoryInterface;
use App\Subject\Domain\Repository\SubjectReadRepositoryInterface;
use App\Subject\Domain\Repository\TeacherSubjectRepositoryInterface;
use App\Subject\Domain\Service\Event\CourseDeletedEvent;

readonly class TeacherSubjectService
{
	public function __construct(
		private TeacherSubjectRepositoryInterface $teacherSubjectRepository,
		private CourseRepositoryInterface         $courseRepository,
		private SubjectReadRepositoryInterface    $subjectReadRepository,
		private UuidProviderInterface             $uuidProvider,
		private EventPublisherInterface           $eventPublisher,
	)
	{
	}

	/**
	 * @throws DomainException
	 */
	public function create(UuidInterface $teacherId, UuidInterface $subjectId): void
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

	/**
	 * @param UuidInterface[] $teacherSubjectIds
	 */
	public function delete(array $teacherSubjectIds): void
	{
		$teacherSubjects = $this->teacherSubjectRepository->findByIds($teacherSubjectIds);
		if (!empty($teacherSubjects))
		{
			$courses = $this->courseRepository->findByTeacherSubjects($teacherSubjectIds);
			$this->eventPublisher->publish(new CourseDeletedEvent(
				array_map(static fn(Course $course) => $course->getCourseId(), $courses),
			));
			$this->courseRepository->delete($courses);
			$this->teacherSubjectRepository->delete($teacherSubjects);
		}
	}

	/**
	 * @throws DomainException
	 */
	private function assertTeacherSubjectNotExists(UuidInterface $teacherId, UuidInterface $subjectId): void
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
	private function assertSubjectExists(UuidInterface $subjectId): void
	{
		$subject = $this->subjectReadRepository->find($subjectId);

		if (is_null($subject))
		{
			throw new DomainException('Subject not found', DomainException::SUBJECT_NOT_FOUND);
		}
	}
}