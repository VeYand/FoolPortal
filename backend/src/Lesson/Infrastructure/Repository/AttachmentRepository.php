<?php
declare(strict_types=1);

namespace App\Lesson\Infrastructure\Repository;

use App\Lesson\Domain\Model\Attachment;
use App\Lesson\Domain\Repository\AttachmentRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class AttachmentRepository implements AttachmentRepositoryInterface
{
	private EntityRepository $repository;

	public function __construct(
		private readonly EntityManagerInterface $entityManager,
	)
	{
		$this->repository = $this->entityManager->getRepository(Attachment::class);
	}

	public function find(string $attachmentId): ?Attachment
	{
		return $this->repository->find($attachmentId);
	}

	public function store(Attachment $attachment): string
	{
		$this->entityManager->persist($attachment);
		$this->entityManager->flush();
		return $attachment->getAttachmentId();
	}

	public function delete(Attachment $attachment): void
	{
		$this->entityManager->remove($attachment);
		$this->entityManager->flush();
	}
}