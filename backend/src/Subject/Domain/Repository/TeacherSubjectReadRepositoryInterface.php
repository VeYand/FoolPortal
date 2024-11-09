<?php
declare(strict_types=1);

namespace App\Subject\Domain\Repository;

use App\Subject\Domain\Model\TeacherSubject;

interface TeacherSubjectReadRepositoryInterface
{
	public function find(string $teacherSubjectId): ?TeacherSubject;

	public function findByTeacherAndSubject(string $teacherId, string $subjectId): ?TeacherSubject;
}