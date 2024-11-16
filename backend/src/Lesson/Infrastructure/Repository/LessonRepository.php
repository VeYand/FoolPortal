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
	public function findByLocation(string $locationId): array
	{
		return $this->repository->findBy([
			'locationId' => $locationId,
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

	public function delete(Lesson $lesson): void
	{
		$this->entityManager->remove($lesson);
		$this->entityManager->flush();
	}
}