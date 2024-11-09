<?php
declare(strict_types=1);

namespace App\Subject\Domain\Repository;

use App\Subject\Domain\Model\Course;

interface CourseReadRepositoryInterface
{
	public function find(string $courseId): ?Course;

	public function findByTeacherSubjectAndGroup(string $teacherSubjectId, string $groupId): ?Course;
}