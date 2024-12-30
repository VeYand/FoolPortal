<?php
declare(strict_types=1);

namespace App\Subject\App\Query;

use App\Subject\App\Query\Data\TeacherSubjectData;
use App\Subject\App\Query\Spec\ListTeacherSubjectsSpec;

interface TeacherSubjectQueryServiceInterface
{
	/**
	 * @return TeacherSubjectData[]
	 */
	public function listTeacherSubjects(ListTeacherSubjectsSpec $spec): array;
}