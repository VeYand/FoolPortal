<?php
declare(strict_types=1);

namespace App\Lesson\App\Adapter;

use App\Common\Uuid\UuidInterface;

interface SubjectAdapterInterface
{
	public function isCourseExists(UuidInterface $courseId): bool;

	/**
	 * @param UuidInterface[] $groupIds
	 * @return UuidInterface[]
	 */
	public function listCourseIdsByGroupIds(array $groupIds): array;

	/**
	 * @param UuidInterface[] $teacherSubjectIds
	 * @return UuidInterface[]
	 */
	public function listCourseIdsByTeacherSubjectIds(array $teacherSubjectIds): array;


	/**
	 * @return UuidInterface[]
	 */
	public function listTeacherSubjectIdsByTeacherId(UuidInterface $teacherId): array;
}