<?php
declare(strict_types=1);

namespace App\Subject\Domain\Service;

use App\Common\Event\EventPublisherInterface;
use App\Common\Uuid\UuidProviderInterface;
use App\Subject\Domain\Exception\DomainException;
use App\Subject\Domain\Model\Course;
use App\Subject\Domain\Model\Subject;
use App\Subject\Domain\Model\TeacherSubject;
use App\Subject\Domain\Repository\CourseRepositoryInterface;
use App\Subject\Domain\Repository\SubjectRepositoryInterface;
use App\Subject\Domain\Repository\TeacherSubjectRepositoryInterface;
use App\Subject\Domain\Service\Event\CourseDeletedEvent;

readonly class SubjectService
{
	public function __construct(
		private SubjectRepositoryInterface        $subjectRepository,
		private TeacherSubjectRepositoryInterface $teacherSubjectRepository,
		private CourseRepositoryInterface         $courseRepository,
		private UuidProviderInterface             $uuidProvider,
		private EventPublisherInterface           $eventPublisher,
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
			throw new DomainException('Subject not found', DomainException::SUBJECT_NOT_FOUND);
		}

		$subject->setName($subjectName);
		$this->subjectRepository->store($subject);
	}

	public function delete(string $subjectId): void
	{
		$subject = $this->subjectRepository->find($subjectId);

		if (!is_null($subject))
		{
			$teacherSubjects = $this->teacherSubjectRepository->findBySubject($subject->getSubjectId());

			if (!empty($teacherSubjects))
			{
				$teacherSubjectIds = array_map(
					static fn(TeacherSubject $teacherSubject) => $teacherSubject->getTeacherSubjectId(),
					$teacherSubjects,
				);
				$courses = $this->courseRepository->findByTeacherSubjects($teacherSubjectIds);
				$this->eventPublisher->publish(new CourseDeletedEvent(
					array_map(static fn(Course $course) => $course->getCourseId(), $courses),
				));
				$this->courseRepository->delete($courses);
				$this->teacherSubjectRepository->delete($teacherSubjects);
			}

			$this->subjectRepository->delete($subject);
		}
	}
}