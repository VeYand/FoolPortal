<?php
declare(strict_types=1);

namespace App\Subject\Domain\Repository;

use App\Subject\Domain\Model\TeacherSubject;

interface TeacherSubjectRepositoryInterface extends TeacherSubjectReadRepositoryInterface
{
	public function store(TeacherSubject $teacherSubject): string;

	public function delete(TeacherSubject $teacherSubject): void;
}