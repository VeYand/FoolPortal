<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Repository;

use App\Common\Uuid\UuidInterface;
use App\Lesson\Domain\Model\Attachment;

interface AttachmentReadRepositoryInterface
{
	public function find(UuidInterface $attachmentId): ?Attachment;

	/**
	 * @param UuidInterface[] $attachmentIds
	 * @return Attachment[]
	 */
	public function findByIds(array $attachmentIds): array;
}