<?php
declare(strict_types=1);

namespace App\Subject\Domain\Repository;

use App\Common\Uuid\UuidInterface;
use App\Subject\Domain\Model\Subject;

interface SubjectReadRepositoryInterface
{
	public function find(UuidInterface $subjectId): ?Subject;

	public function findByName(string $subjectName): ?Subject;

	/**
	 * @return Subject[]
	 */
	public function findAll(): array;
}