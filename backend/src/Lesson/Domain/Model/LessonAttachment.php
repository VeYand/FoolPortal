<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Model;

use App\Common\Uuid\UuidInterface;

readonly class LessonAttachment
{
	public function __construct(
		private UuidInterface $lessonAttachmentId,
		private UuidInterface $lessonId,
		private UuidInterface $attachmentId,
	)
	{
	}

	public function getLessonAttachmentId(): UuidInterface
	{
		return $this->lessonAttachmentId;
	}

	public function getLessonId(): UuidInterface
	{
		return $this->lessonId;
	}

	public function getAttachmentId(): UuidInterface
	{
		return $this->attachmentId;
	}
}