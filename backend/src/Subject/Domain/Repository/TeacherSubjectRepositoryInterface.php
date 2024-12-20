<?php
declare(strict_types=1);

namespace App\Subject\Domain\Repository;

use App\Subject\Domain\Model\TeacherSubject;

interface TeacherSubjectRepositoryInterface extends TeacherSubjectReadRepositoryInterface
{
	public function store(TeacherSubject $teacherSubject): string;

	/**
	 * @param TeacherSubject[] $teacherSubjects
	 */
	public function delete(array $teacherSubjects): void;
}