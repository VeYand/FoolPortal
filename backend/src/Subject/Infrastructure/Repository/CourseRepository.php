<?php
declare(strict_types=1);

namespace App\Subject\Infrastructure\Repository;

use App\Common\Uuid\UuidInterface;
use App\Common\Uuid\UuidUtils;
use App\Subject\Domain\Model\Course;
use App\Subject\Domain\Repository\CourseRepositoryInterface;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class CourseRepository implements CourseRepositoryInterface
{
	private EntityRepository $repository;

	public function __construct(
		private readonly EntityManagerInterface $entityManager,
	)
	{
		$this->repository = $this->entityManager->getRepository(Course::class);
	}

	public function find(UuidInterface $courseId): ?Course
	{
		return $this->repository->find($courseId);
	}

	/**
	 * @param UuidInterface[] $courseIds
	 * @return Course[]
	 */
	public function findByIds(array $courseIds): array
	{
		$qb = $this->repository->createQueryBuilder('c');
		return $qb
			->where($qb->expr()->in('c.courseId', ':courseIds'))
			->setParameter('courseIds', UuidUtils::convertToBinaryList($courseIds), ArrayParameterType::STRING)
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

	public function findByTeacherSubjectAndGroup(UuidInterface $teacherSubjectId, UuidInterface $groupId): ?Course
	{
		return $this->repository->findOneBy([
			'teacherSubjectId' => $teacherSubjectId,
			'groupId' => $groupId,
		]);
	}

	/**
	 * @inheritDoc
	 */
	public function findByTeacherSubjects(array $teacherSubjectIds): array
	{
		$qb = $this->repository->createQueryBuilder('c');
		return $qb
			->where($qb->expr()->in('c.teacherSubjectId', ':teacherSubjectIds'))
			->setParameter('teacherSubjectIds', UuidUtils::convertToBinaryList($teacherSubjectIds), ArrayParameterType::STRING)
			->getQuery()
			->getResult();
	}

	/**
	 * @inheritDoc
	 */
	public function findByGroups(array $groupIds): array
	{
		$qb = $this->repository->createQueryBuilder('c');
		return $qb
			->where($qb->expr()->in('c.groupId', ':groupIds'))
			->setParameter('groupIds', UuidUtils::convertToBinaryList($groupIds), ArrayParameterType::STRING)
			->getQuery()
			->getResult();
	}

	public function store(Course $course): UuidInterface
	{
		$this->entityManager->persist($course);
		$this->entityManager->flush();
		return $course->getCourseId();
	}

	/**
	 * @inheritDoc
	 */
	public function delete(array $courses): void
	{
		foreach ($courses as $course)
		{
			$this->entityManager->remove($course);
		}
		$this->entityManager->flush();
	}
}