<?php
declare(strict_types=1);

namespace App\Lesson\Infrastructure\Repository;

use App\Lesson\Domain\Model\Lesson;
use App\Lesson\Domain\Repository\LessonRepositoryInterface;
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

	public function find(string $lessonId): ?Lesson
	{
		return $this->repository->find($lessonId);
	}

	/**
	 * @inheritDoc
	 */
	public function findByIds(array $lessonIds): array
	{
		return $this->repository->findBy([
			'lessonId' => $lessonIds,
		]);
	}

	/**
	 * @inheritDoc
	 */
	public function findByLocation(string $locationId): array
	{
		return $this->repository->findBy([
			'locationId' => $locationId,
		]);
	}

	/**
	 * @inheritDoc
	 */
	public function findByTimeInterval(\DateTimeInterface $startTime, \DateTimeInterface $endTime): array
	{
		$qb = $this->entityManager->createQueryBuilder();

		$qb->select('l')
			->from(Lesson::class, 'l')
			->where('l.date >= :startDate')
			->andWhere('l.date <= :endDate')
			->andWhere('l.startTime + l.duration <= :endTime')
			->setParameter('startDate', $startTime)
			->setParameter('endDate', $endTime)
			->setParameter('endTime', $endTime->getTimestamp());

		return $qb->getQuery()->getResult();
	}

	/**
	 * @inheritDoc
	 */
	public function findByCourses(array $courseIds): array
	{
		return $this->repository->findBy([
			'courseId' => $courseIds,
		]);
	}

	public function store(Lesson $lesson): string
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