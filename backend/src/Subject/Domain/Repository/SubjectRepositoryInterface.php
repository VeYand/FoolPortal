<?php
declare(strict_types=1);

namespace App\Subject\Domain\Repository;

use App\Subject\Domain\Model\Subject;

interface SubjectRepositoryInterface extends SubjectReadRepositoryInterface
{
	public function store(Subject $subject): string;

	public function delete(Subject $subject): void;
}