<?php
declare(strict_types=1);

namespace App\Subject\Infrastructure\Repository;

use App\Subject\Domain\Model\TeacherSubject;
use App\Subject\Domain\Repository\TeacherSubjectRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class TeacherSubjectRepository implements TeacherSubjectRepositoryInterface
{
	private EntityRepository $repository;

	public function __construct(
		private readonly EntityManagerInterface $entityManager,
	)
	{
		$this->repository = $this->entityManager->getRepository(TeacherSubject::class);
	}

	public function find(string $teacherSubjectId): ?TeacherSubject
	{
		return $this->repository->find($teacherSubjectId);
	}

	public function findByTeacherAndSubject(string $teacherId, string $subjectId): ?TeacherSubject
	{
		return $this->repository->findOneBy([
			'teacherId' => $teacherId,
			'subjectId' => $subjectId,
		]);
	}

	public function store(TeacherSubject $teacherSubject): string
	{
		$this->entityManager->persist($teacherSubject);
		$this->entityManager->flush();
		return $teacherSubject->getSubjectId();
	}

	public function delete(TeacherSubject $teacherSubject): void
	{
		$this->entityManager->remove($teacherSubject);
		$this->entityManager->flush();
	}
}