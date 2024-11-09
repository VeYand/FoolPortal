<?php
declare(strict_types=1);

namespace App\Subject\Domain\Repository;

use App\Subject\Domain\Model\Subject;

interface SubjectReadRepositoryInterface
{
	public function find(string $subjectId): ?Subject;
}