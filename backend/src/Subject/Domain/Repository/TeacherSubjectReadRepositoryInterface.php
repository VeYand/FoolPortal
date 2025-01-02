<?php
declare(strict_types=1);

namespace App\Subject\Domain\Repository;

use App\Common\Uuid\UuidInterface;
use App\Subject\Domain\Model\TeacherSubject;

interface TeacherSubjectReadRepositoryInterface
{
	public function find(UuidInterface $teacherSubjectId): ?TeacherSubject;

	/**
	 * @param UuidInterface[] $teacherSubjectIds
	 * @return TeacherSubject[]
	 */
	public function findByIds(array $teacherSubjectIds): array;

	/**
	 * @return TeacherSubject[]
	 */
	public function findAll(): array;

	public function findByTeacherAndSubject(UuidInterface $teacherId, UuidInterface $subjectId): ?TeacherSubject;

	/**
	 * @return TeacherSubject[]
	 */
	public function findBySubject(UuidInterface $subjectId): array;

	/**
	 * @param UuidInterface[] $teacherIds
	 * @return TeacherSubject[]
	 */
	public function findByTeachers(array $teacherIds): array;
}