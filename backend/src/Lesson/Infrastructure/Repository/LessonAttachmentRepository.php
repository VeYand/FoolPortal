<?php
declare(strict_types=1);

namespace App\Lesson\Infrastructure\Repository;

use App\Common\Uuid\UuidInterface;
use App\Common\Uuid\UuidUtils;
use App\Lesson\Domain\Model\LessonAttachment;
use App\Lesson\Domain\Repository\LessonAttachmentRepositoryInterface;
use Doctrine\DBAL\ArrayParameterType;
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

	public function findByLessonAndAttachment(UuidInterface $lessonId, UuidInterface $attachmentId): ?LessonAttachment
	{
		return $this->repository->findOneBy([
			'lessonId' => $lessonId,
			'attachmentId' => $attachmentId,
		]);
	}

	/**
	 * @inheritDoc
	 */
	public function findByAttachment(UuidInterface $attachmentId): array
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
		$qb = $this->repository->createQueryBuilder('la');
		return $qb
			->where($qb->expr()->in('la.lessonId', ':lessonIds'))
			->setParameter('lessonIds', UuidUtils::convertToBinaryList($lessonIds), ArrayParameterType::STRING)
			->getQuery()
			->getResult();
	}

	public function store(LessonAttachment $lessonAttachment): UuidInterface
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