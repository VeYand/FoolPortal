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

	public function find(string $lessonAttachmentId): ?LessonAttachment
	{
		return $this->repository->find($lessonAttachmentId);
	}

	public function findByLessonAndAttachment(string $lessonId, string $attachmentId): ?LessonAttachment
	{
		return $this->repository->findOneBy([
			'lessonId' => $lessonId,
			'attachmentId' => $attachmentId,
		]);
	}

	public function store(LessonAttachment $lessonAttachment): string
	{
		$this->entityManager->persist($lessonAttachment);
		$this->entityManager->flush();
		return $lessonAttachment->getLessonAttachmentId();
	}

	public function delete(LessonAttachment $lessonAttachment): void
	{
		$this->entityManager->remove($lessonAttachment);
		$this->entityManager->flush();
	}
}