<?php
declare(strict_types=1);

namespace App\Subject\Infrastructure\Repository;

use App\Common\Uuid\UuidInterface;
use App\Common\Uuid\UuidUtils;
use App\Subject\Domain\Model\TeacherSubject;
use App\Subject\Domain\Repository\TeacherSubjectRepositoryInterface;
use Doctrine\DBAL\ArrayParameterType;
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

	public function find(UuidInterface $teacherSubjectId): ?TeacherSubject
	{
		return $this->repository->find($teacherSubjectId);
	}

	/**
	 * @inheritDoc
	 */
	public function findByIds(array $teacherSubjectIds): array
	{
		$qb = $this->repository->createQueryBuilder('ts');
		return $qb
			->where($qb->expr()->in('ts.teacherSubjectId', ':teacherSubjectIds'))
			->setParameter('teacherSubjectIds', UuidUtils::convertToBinaryList($teacherSubjectIds), ArrayParameterType::STRING)
			->getQuery()
			->getResult();
	}


	/**
	 * @inheritDoc
	 */
	public function findAll(): array
	{
		return $this->repository->findAll();
	}

	public function findByTeacherAndSubject(UuidInterface $teacherId, UuidInterface $subjectId): ?TeacherSubject
	{
		return $this->repository->findOneBy([
			'teacherId' => $teacherId,
			'subjectId' => $subjectId,
		]);
	}

	/**
	 * @inheritDoc
	 */
	public function findBySubject(UuidInterface $subjectId): array
	{
		return $this->repository->findBy([
			'subjectId' => $subjectId,
		]);
	}

	/**
	 * @inheritDoc
	 */
	public function findByTeachers(array $teacherIds): array
	{
		$qb = $this->repository->createQueryBuilder('ts');
		return $qb
			->where($qb->expr()->in('ts.teacherId', ':teacherIds'))
			->setParameter('teacherIds', UuidUtils::convertToBinaryList($teacherIds), ArrayParameterType::STRING)
			->getQuery()
			->getResult();
	}

	public function store(TeacherSubject $teacherSubject): UuidInterface
	{
		$this->entityManager->persist($teacherSubject);
		$this->entityManager->flush();
		return $teacherSubject->getSubjectId();
	}

	public function delete(array $teacherSubjects): void
	{
		foreach ($teacherSubjects as $teacherSubject)
		{
			$this->entityManager->remove($teacherSubject);
		}
		$this->entityManager->flush();
	}
}