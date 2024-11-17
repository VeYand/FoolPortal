<?php
declare(strict_types=1);

namespace App\Subject\Domain\Service;

use App\Common\Uuid\UuidProviderInterface;
use App\Subject\Domain\Exception\DomainException;
use App\Subject\Domain\Model\Course;
use App\Subject\Domain\Repository\CourseRepositoryInterface;
use App\Subject\Domain\Repository\TeacherSubjectReadRepositoryInterface;

readonly class CourseService
{
	public function __construct(
		private CourseRepositoryInterface             $courseRepository,
		private TeacherSubjectReadRepositoryInterface $teacherSubjectReadRepository,
		private UuidProviderInterface                 $uuidProvider,
	)
	{
	}

	/**
	 * @throws DomainException
	 */
	public function create(string $teacherSubjectId, string $groupId): string
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

	public function delete(string $courseId): void
	{
		$course = $this->courseRepository->find($courseId);

		if (!is_null($course))
		{
			$this->courseRepository->delete([$course]);
		}
	}

	public function deleteByGroup(string $groupId): void // TODO оптимизировать
	{
		$courses = $this->courseRepository->findByGroup($groupId);

		foreach ($courses as $course)
		{
			$this->delete($course->getCourseId());
		}
	}

	/**
	 * @throws DomainException
	 */
	private function assertCourseNotExists(string $teacherSubjectId, string $groupId): void
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
	private function assertTeacherSubjectExists(string $teacherSubjectId): void
	{
		$teacherSubject = $this->teacherSubjectReadRepository->find($teacherSubjectId);

		if (is_null($teacherSubject))
		{
			throw new DomainException('Teacher subject not found', DomainException::TEACHER_SUBJECT_NOT_FOUND);
		}
	}
}