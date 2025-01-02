<?php
declare(strict_types=1);

namespace App\Lesson\Infrastructure\Repository;

use App\Common\Uuid\UuidInterface;
use App\Common\Uuid\UuidUtils;
use App\Lesson\Domain\Model\Lesson;
use App\Lesson\Domain\Repository\LessonRepositoryInterface;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class LessonRepository implements LessonRepositoryInterface
{
	private EntityRepository $repository;

	public function __construct(
		private readonly EntityManagerInterface $entityManager,
	)
	{
		$this->repository = $this->entityManager->getRepository(Lesson::class);
	}

	public function find(UuidInterface $lessonId): ?Lesson
	{
		return $this->repository->find($lessonId);
	}

	/**
	 * @inheritDoc
	 */
	public function findByIds(array $lessonIds): array
	{
		$qb = $this->repository->createQueryBuilder('l');
		return $qb
			->where($qb->expr()->in('l.lessonId', ':lessonIds'))
			->setParameter('lessonIds', UuidUtils::convertToBinaryList($lessonIds), ArrayParameterType::STRING)
			->getQuery()
			->getResult();
	}

	/**
	 * @inheritDoc
	 */
	public function findByLocation(UuidInterface $locationId): array
	{
		return $this->repository->findBy([
			'locationId' => $locationId,
		]);
	}

	/**
	 * @inheritDoc
	 */
	public function findByCourses(array $courseIds): array
	{
		$qb = $this->repository->createQueryBuilder('l');
		return $qb
			->where($qb->expr()->in('l.courseId', ':courseIds'))
			->setParameter('courseIds', UuidUtils::convertToBinaryList($courseIds), ArrayParameterType::STRING)
			->getQuery()
			->getResult();
	}

	public function store(Lesson $lesson): UuidInterface
	{
		$this->entityManager->persist($lesson);
		$this->entityManager->flush();
		return $lesson->getLessonId();
	}

	/**
	 * @inheritDoc
	 */
	public function storeList(array $lessons): void
	{
		foreach ($lessons as $lesson)
		{
			$this->entityManager->persist($lesson);
		}
		$this->entityManager->flush();
	}

	/**
	 * @param Lesson[] $lessons
	 */
	public function delete(array $lessons): void
	{
		foreach ($lessons as $lesson)
		{
			$this->entityManager->remove($lesson);
		}
		$this->entityManager->flush();
	}
}