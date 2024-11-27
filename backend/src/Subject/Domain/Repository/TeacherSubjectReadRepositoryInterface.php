<?php
declare(strict_types=1);

namespace App\Subject\Domain\Repository;

use App\Subject\Domain\Model\TeacherSubject;

interface TeacherSubjectReadRepositoryInterface
{
	public function find(string $teacherSubjectId): ?TeacherSubject;

	/**
	 * @param string[] $teacherSubjectIds
	 * @return TeacherSubject[]
	 */
	public function findByIds(array $teacherSubjectIds): array;

	/**
	 * @return TeacherSubject[]
	 */
	public function findAll(): array;

	public function findByTeacherAndSubject(string $teacherId, string $subjectId): ?TeacherSubject;

	/**
	 * @return TeacherSubject[]
	 */
	public function findBySubject(string $subjectId): array;

	/**
	 * @return TeacherSubject[]
	 */
	public function findByGroup(string $groupId): array;

	/**
	 * @param string[] $teacherIds
	 * @return TeacherSubject[]
	 */
	public function findByTeachers(array $teacherIds): array;
}