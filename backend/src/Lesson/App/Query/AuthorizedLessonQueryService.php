<?php
declare(strict_types=1);

namespace App\Lesson\App\Query;

use App\Common\Uuid\UuidInterface;
use App\Lesson\App\Adapter\Data\UserRole;
use App\Lesson\App\Adapter\SessionAdapterInterface;
use App\Lesson\App\Adapter\SubjectAdapterInterface;
use App\Lesson\App\Adapter\UserAdapterInterface;
use App\Lesson\App\Query\Data\LessonData;

readonly class AuthorizedLessonQueryService implements LessonQueryServiceInterface
{
	public function __construct(
		private LessonQueryServiceInterface $lessonQueryService,
		private SessionAdapterInterface     $sessionAdapter,
		private UserAdapterInterface        $userAdapter,
		private SubjectAdapterInterface     $subjectAdapter,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function listByTimeInterval(\DateTimeInterface $startTime, \DateTimeInterface $endTime): array
	{
		$lessons = $this->lessonQueryService->listByTimeInterval($startTime, $endTime);

		$currentUserId = $this->sessionAdapter->getCurrentUserId();
		$currentUserRole = $this->sessionAdapter->getCurrentUserRole();
		return match ($currentUserRole)
		{
			UserRole::OWNER, UserRole::ADMIN => $lessons,
			UserRole::STUDENT => $this->filterLessonsForStudent($lessons, $currentUserId),
			UserRole::TEACHER => $this->filterLessonsForTeacher($lessons, $currentUserId),
		};
	}

	/**
	 * @param LessonData[] $lessons
	 * @return LessonData[]
	 */
	private function filterLessonsForStudent(array $lessons, UuidInterface $studentId): array
	{
		$currentUserGroupIds = $this->userAdapter->listUserGroupIds($studentId);
		$currentUserCourseIds = $this->subjectAdapter->listCourseIdsByGroupIds($currentUserGroupIds);

		return array_filter(
			$lessons,
			static fn(LessonData $lesson) => in_array($lesson->courseId, $currentUserCourseIds),
		);
	}

	/**
	 * @param LessonData[] $lessons
	 * @return LessonData[]
	 */
	private function filterLessonsForTeacher(array $lessons, UuidInterface $teacherId): array
	{
		$teacherSubjectIds = $this->subjectAdapter->listTeacherSubjectIdsByTeacherId($teacherId);
		$courseIds = $this->subjectAdapter->listCourseIdsByTeacherSubjectIds($teacherSubjectIds);

		return array_filter(
			$lessons,
			static fn(LessonData $lesson) => in_array($lesson->courseId, $courseIds),
		);
	}
}