<?php
declare(strict_types=1);

namespace App\Subject\Domain\Repository;

use App\Subject\Domain\Model\Course;

interface CourseReadRepositoryInterface
{
	public function find(string $courseId): ?Course;

	public function findByGroup(string $groupId): ?Course;

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
}