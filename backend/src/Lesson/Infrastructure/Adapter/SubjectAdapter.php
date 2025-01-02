<?php
declare(strict_types=1);

namespace App\Lesson\Infrastructure\Adapter;

use App\Common\Uuid\UuidInterface;
use App\Lesson\App\Adapter\SubjectAdapterInterface;
use App\Subject\Api\SubjectApiInterface;
use App\Subject\App\Query\Data\CourseData;
use App\Subject\App\Query\Data\TeacherSubjectData;
use App\Subject\App\Query\Spec\ListCoursesSpec;
use App\Subject\App\Query\Spec\ListTeacherSubjectsSpec;

readonly class SubjectAdapter implements SubjectAdapterInterface
{
	public function __construct(
		private SubjectApiInterface $subjectApi,
	)
	{
	}

	public function isCourseExists(UuidInterface $courseId): bool
	{
		return $this->subjectApi->isCourseExists($courseId);
	}

	/**
	 * @inheritDoc
	 */
	public function listCourseIdsByGroupIds(array $groupIds): array
	{
		$courses = $this->subjectApi->listCourses(new ListCoursesSpec(groupIds: $groupIds));
		return array_map(static fn(CourseData $course) => $course->courseId, $courses);

	}

	/**
	 * @inheritDoc
	 */
	public function listCourseIdsByTeacherSubjectIds(array $teacherSubjectIds): array
	{
		$courses = $this->subjectApi->listCourses(new ListCoursesSpec(teacherSubjectIds: $teacherSubjectIds));
		return array_map(static fn(CourseData $course) => $course->courseId, $courses);
	}

	/**
	 * @inheritDoc
	 */
	public function listTeacherSubjectIdsByTeacherId(UuidInterface $teacherId): array
	{
		$teacherSubjects = $this->subjectApi->listTeacherSubjects(new ListTeacherSubjectsSpec(teacherIds: [$teacherId]));
		return array_map(static fn(TeacherSubjectData $teacherSubjectData) => $teacherSubjectData->teacherSubjectId, $teacherSubjects);
	}
}