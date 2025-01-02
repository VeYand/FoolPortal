<?php
declare(strict_types=1);

namespace App\Subject\Domain\Repository;

use App\Common\Uuid\UuidInterface;
use App\Subject\Domain\Model\Course;

interface CourseReadRepositoryInterface
{
	public function find(UuidInterface $courseId): ?Course;

	/**
	 * @param UuidInterface[] $courseIds
	 * @return Course[]
	 */
	public function findByIds(array $courseIds): array;

	/**
	 * @return Course[]
	 */
	public function findAll(): array;

	public function findByTeacherSubjectAndGroup(UuidInterface $teacherSubjectId, UuidInterface $groupId): ?Course;

	/**
	 * @param UuidInterface[] $teacherSubjectIds
	 * @return Course[]
	 */
	public function findByTeacherSubjects(array $teacherSubjectIds): array;

	/**
	 * @param UuidInterface[] $groupIds
	 * @return Course[]
	 */
	public function findByGroups(array $groupIds): array;
}