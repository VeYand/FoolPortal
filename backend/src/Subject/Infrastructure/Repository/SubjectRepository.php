<?php
declare(strict_types=1);

namespace App\Subject\Infrastructure\Repository;

use App\Subject\Domain\Model\Subject;
use App\Subject\Domain\Repository\SubjectRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class SubjectRepository implements SubjectRepositoryInterface
{
	private EntityRepository $repository;

	public function __construct(
		private readonly EntityManagerInterface $entityManager,
	)
	{
		$this->repository = $this->entityManager->getRepository(Subject::class);
	}

	public function find(string $subjectId): ?Subject
	{
		return $this->repository->find($subjectId);
	}

	public function store(Subject $subject): string
	{
		$this->entityManager->persist($subject);
		$this->entityManager->flush();
		return $subject->getSubjectId();
	}

	public function delete(Subject $subject): void
	{
		$this->entityManager->remove($subject);
		$this->entityManager->flush();
	}
}