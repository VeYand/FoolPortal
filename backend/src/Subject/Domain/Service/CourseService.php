<?php
declare(strict_types=1);

namespace App\Subject\Domain\Service;

use App\Common\Event\EventPublisherInterface;
use App\Common\Uuid\UuidInterface;
use App\Common\Uuid\UuidProviderInterface;
use App\Subject\Domain\Exception\DomainException;
use App\Subject\Domain\Model\Course;
use App\Subject\Domain\Repository\CourseRepositoryInterface;
use App\Subject\Domain\Repository\TeacherSubjectReadRepositoryInterface;
use App\Subject\Domain\Service\Event\CourseDeletedEvent;

readonly class CourseService
{
	public function __construct(
		private CourseRepositoryInterface             $courseRepository,
		private TeacherSubjectReadRepositoryInterface $teacherSubjectReadRepository,
		private UuidProviderInterface                 $uuidProvider,
		private EventPublisherInterface               $eventPublisher,
	)
	{
	}

	/**
	 * @throws DomainException
	 */
	public function create(UuidInterface $teacherSubjectId, UuidInterface $groupId): UuidInterface
	{
		$this->assertTeacherSubjectExists($teacherSubjectId);
		$this->assertCourseNotExists($teacherSubjectId, $groupId);

		$course = new Course(
			$this->uuidProvider->generate(),
			$teacherSubjectId,
			$groupId,
		);

		return $this->courseRepository->store($course);
	}

	/**
	 * @param UuidInterface[] $courseIds
	 */
	public function delete(array $courseIds): void
	{
		$courses = $this->courseRepository->findByIds($courseIds);

		if (!empty($courses))
		{
			$this->courseRepository->delete($courses);
			$this->eventPublisher->publish(new CourseDeletedEvent($courseIds));
		}
	}

	/**
	 * @throws DomainException
	 */
	private function assertCourseNotExists(UuidInterface $teacherSubjectId, UuidInterface $groupId): void
	{
		$course = $this->courseRepository->findByTeacherSubjectAndGroup($teacherSubjectId, $groupId);

		if (!is_null($course))
		{
			throw new DomainException('Course already exists', DomainException::COURSE_ALREADY_EXISTS);
		}
	}

	/**
	 * @throws DomainException
	 */
	private function assertTeacherSubjectExists(UuidInterface $teacherSubjectId): void
	{
		$teacherSubject = $this->teacherSubjectReadRepository->find($teacherSubjectId);

		if (is_null($teacherSubject))
		{
			throw new DomainException('Teacher subject not found', DomainException::TEACHER_SUBJECT_NOT_FOUND);
		}
	}
}