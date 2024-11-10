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

	public function store(Lesson $lesson): string
	{
		$this->entityManager->persist($lesson);
		$this->entityManager->flush();
		return $lesson->getLessonId();
	}

	public function delete(Lesson $lesson): void
	{
		$this->entityManager->remove($lesson);
		$this->entityManager->flush();
	}
}