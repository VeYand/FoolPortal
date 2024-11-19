<?php
declare(strict_types=1);

namespace App\Tests\Unit\Lesson\Infrastructure;

use App\Lesson\Domain\Model\LessonAttachment;
use App\Lesson\Domain\Repository\LessonAttachmentRepositoryInterface;

class LessonAttachmentInMemoryRepository implements LessonAttachmentRepositoryInterface
{
	/** @var array<string, LessonAttachment> */
	private array $attachments = [];

	public function findByLessonAndAttachment(string $lessonId, string $attachmentId): ?LessonAttachment
	{
		foreach ($this->attachments as $attachment)
		{
			if ($attachment->getLessonId() === $lessonId && $attachment->getAttachmentId() === $attachmentId)
			{
				return $attachment;
			}
		}
		return null;
	}

	/**
	 * @inheritDoc
	 */
	public function findByAttachment(string $attachmentId): array
	{
		return array_values(array_filter(
			$this->attachments,
			static fn(LessonAttachment $attachment) => $attachment->getAttachmentId() === $attachmentId,
		));
	}

	/**
	 * @inheritDoc
	 */
	public function findByLessons(array $lessonIds): array
	{
		return array_values(array_filter(
			$this->attachments,
			static fn(LessonAttachment $attachment) => in_array($attachment->getLessonId(), $lessonIds, true),
		));
	}

	public function store(LessonAttachment $lessonAttachment): string
	{
		$this->attachments[$lessonAttachment->getLessonAttachmentId()] = $lessonAttachment;
		return $lessonAttachment->getLessonAttachmentId();
	}

	/**
	 * @inheritDoc
	 */
	public function delete(array $lessonAttachments): void
	{
		foreach ($lessonAttachments as $lessonAttachment)
		{
			if (!$lessonAttachment instanceof LessonAttachment)
			{
				throw new \InvalidArgumentException('All items must be instances of LessonAttachment.');
			}
			unset($this->attachments[$lessonAttachment->getLessonAttachmentId()]);
		}
	}
}
