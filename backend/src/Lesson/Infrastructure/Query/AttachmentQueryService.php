<?php
declare(strict_types=1);

namespace App\Lesson\Infrastructure\Query;

use App\Lesson\App\Query\AttachmentQueryServiceInterface;
use App\Lesson\App\Query\Data\AttachmentData;
use App\Lesson\Domain\Model\Attachment;
use App\Lesson\Domain\Model\LessonAttachment;
use App\Lesson\Domain\Repository\AttachmentReadRepositoryInterface;
use App\Lesson\Domain\Repository\LessonAttachmentReadRepositoryInterface;

readonly class AttachmentQueryService implements AttachmentQueryServiceInterface
{
	public function __construct(
		private LessonAttachmentReadRepositoryInterface $lessonAttachmentReadRepository,
		private AttachmentReadRepositoryInterface       $attachmentReadRepository,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function listLessonAttachments(string $lessonId): array
	{
		$lessonAttachments = $this->lessonAttachmentReadRepository->findByLessons([$lessonId]);
		$attachmentIds = array_map(static fn(LessonAttachment $lessonAttachment) => $lessonAttachment->getAttachmentId(), $lessonAttachments);
		$attachments = $this->attachmentReadRepository->findByIds($attachmentIds);

		return array_map(static fn(Attachment $attachment) => new AttachmentData(
			$attachment->getAttachmentId(),
			$attachment->getExtension(),
			$attachment->getName(),
			$attachment->getDescription(),
		), $attachments);
	}
}