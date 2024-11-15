<?php
declare(strict_types=1);

namespace App\Subject\App\Query;

use App\Subject\App\Query\Data\SubjectData;

interface SubjectQueryServiceInterface
{
	/**
	 * @return SubjectData[]
	 */
	public function listAllSubjects(): array;
}