<?php
declare(strict_types=1);

namespace App\Subject\App\Query;

use App\Subject\App\Query\Data\TeacherSubjectData;

interface TeacherSubjectQueryServiceInterface
{
	/**
	 * @return TeacherSubjectData[]
	 */
	public function listAllTeacherSubjects(): array;
}