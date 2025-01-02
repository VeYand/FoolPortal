<?php
declare(strict_types=1);

namespace App\Lesson\Infrastructure\Repository;

use App\Common\Uuid\UuidInterface;
use App\Common\Uuid\UuidUtils;
use App\Lesson\Domain\Model\Attachment;
use App\Lesson\Domain\Repository\AttachmentRepositoryInterface;
use Doctrine\DBAL\ArrayParameterType;
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

	public function find(UuidInterface $attachmentId): ?Attachment
	{
		return $this->repository->find($attachmentId);
	}

	/**
	 * @inheritDoc
	 */
	public function findByIds(array $attachmentIds): array
	{
		$qb = $this->repository->createQueryBuilder('a');
		return $qb
			->where($qb->expr()->in('a.attachmentId', ':attachmentIds'))
			->setParameter('attachmentIds', UuidUtils::convertToBinaryList($attachmentIds), ArrayParameterType::STRING)
			->getQuery()
			->getResult();
	}

	public function store(Attachment $attachment): UuidInterface
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