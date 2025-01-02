<?php
declare(strict_types=1);

namespace App\Subject\Domain\Repository;

use App\Common\Uuid\UuidInterface;
use App\Subject\Domain\Model\Subject;

interface SubjectRepositoryInterface extends SubjectReadRepositoryInterface
{
	public function store(Subject $subject): UuidInterface;

	public function delete(Subject $subject): void;
}