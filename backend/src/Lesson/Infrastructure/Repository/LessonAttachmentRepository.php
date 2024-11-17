<?php
declare(strict_types=1);

namespace App\Lesson\Infrastructure\Repository;

use App\Lesson\Domain\Model\LessonAttachment;
use App\Lesson\Domain\Repository\LessonAttachmentRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class LessonAttachmentRepository implements LessonAttachmentRepositoryInterface
{
	private EntityRepository $repository;

	public function __construct(
		private readonly EntityManagerInterface $entityManager,
	)
	{
		$this->repository = $this->entityManager->getRepository(LessonAttachment::class);
	}

	public function findByLessonAndAttachment(string $lessonId, string $attachmentId): ?LessonAttachment
	{
		return $this->repository->findOneBy([
			'lessonId' => $lessonId,
			'attachmentId' => $attachmentId,
		]);
	}

	/**
	 * @inheritDoc
	 */
	public function findByAttachment(string $attachmentId): array
	{
		return $this->repository->findBy([
			'attachmentId' => $attachmentId,
		]);
	}

	/**
	 * @inheritDoc
	 */
	public function findByLessons(array $lessonIds): array
	{
		return $this->repository->findBy([
			'lessonId' => $lessonIds,
		]);
	}

	public function store(LessonAttachment $lessonAttachment): string
	{
		$this->entityManager->persist($lessonAttachment);
		$this->entityManager->flush();
		return $lessonAttachment->getLessonAttachmentId();
	}

	/**
	 * @inheritDoc
	 */
	public function delete(array $lessonAttachments): void
	{
		foreach ($lessonAttachments as $lessonAttachment)
		{
			$this->entityManager->remove($lessonAttachment);
		}
		$this->entityManager->flush();
	}
}