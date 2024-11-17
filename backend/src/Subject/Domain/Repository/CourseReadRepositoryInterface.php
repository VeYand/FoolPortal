<?php
declare(strict_types=1);

namespace App\Subject\Domain\Repository;

use App\Subject\Domain\Model\Course;

interface CourseReadRepositoryInterface
{
	public function find(string $courseId): ?Course;

	/**
	 * @param string[] $courseIds
	 * @return Course[]
	 */
	public function findByIds(array $courseIds): array;

	/**
	 * @return Course[]
	 */
	public function findAll(): array;

	public function findByTeacherSubjectAndGroup(string $teacherSubjectId, string $groupId): ?Course;

	/**
	 * @param string[] $teacherSubjectIds
	 * @return Course[]
	 */
	public function findByTeacherSubjects(array $teacherSubjectIds): array;

	/**
	 * @param string[] $groupIds
	 * @return Course[]
	 */
	public function findByGroups(array $groupIds): array;
}