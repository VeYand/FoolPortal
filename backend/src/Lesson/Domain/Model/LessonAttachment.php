<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Model;

readonly class LessonAttachment
{
	public function __construct(
		private string $lessonAttachmentId,
		private string $lessonId,
		private string $attachmentId,
	)
	{
	}

	public function getLessonAttachmentId(): string
	{
		return $this->lessonAttachmentId;
	}

	public function getLessonId(): string
	{
		return $this->lessonId;
	}

	public function getAttachmentId(): string
	{
		return $this->attachmentId;
	}
}