<?php
declare(strict_types=1);

namespace App\Lesson\Infrastructure\Query;

use App\Lesson\App\Query\AttachmentQueryServiceInterface;
use App\Lesson\App\Query\Data\AttachmentData;
use App\Lesson\Domain\Model\Attachment;
use App\Lesson\Domain\Repository\AttachmentReadRepositoryInterface;

readonly class AttachmentQueryService implements AttachmentQueryServiceInterface
{
	public function __construct(
		private AttachmentReadRepositoryInterface $attachmentReadRepository,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function listAttachmentsByIds(array $attachmentIds): array
	{
		$attachments = $this->attachmentReadRepository->findByIds($attachmentIds);

		return array_map(static fn(Attachment $attachment) => new AttachmentData(
			$attachment->getAttachmentId(),
			$attachment->getName(),
			$attachment->getDescription(),
		), $attachments);
	}
}