<?php
declare(strict_types=1);

namespace App\Tests\Unit\Lesson\Infrastructure;

use App\Common\Uuid\UuidInterface;
use App\Lesson\Domain\Model\LessonAttachment;
use App\Lesson\Domain\Repository\LessonAttachmentRepositoryInterface;

class InMemoryLessonAttachmentRepository implements LessonAttachmentRepositoryInterface
{
	/** @var array<string, LessonAttachment> */
	private array $lessonAttachments = [];

	public function findByLessonAndAttachment(UuidInterface $lessonId, UuidInterface $attachmentId): ?LessonAttachment
	{
		foreach ($this->lessonAttachments as $la)
		{
			if ($la->getLessonId()->toString() === $lessonId->toString()
				&& $la->getAttachmentId()->toString() === $attachmentId->toString()
			)
			{
				return $la;
			}
		}
		return null;
	}

	/**
	 * @inheritDoc
	 */
	public function findByAttachment(UuidInterface $attachmentId): array
	{
		throw new \BadMethodCallException('Not implemented');
	}

	/**
	 * @inheritDoc
	 */
	public function findByLessons(array $lessonIds): array
	{
		throw new \BadMethodCallException('Not implemented');
	}

	public function store(LessonAttachment $lessonAttachment): UuidInterface
	{
		$this->lessonAttachments[$lessonAttachment->getLessonAttachmentId()->toString()] = $lessonAttachment;
		return $lessonAttachment->getLessonAttachmentId();
	}

	/**
	 * @inheritDoc
	 */
	public function delete(array $lessonAttachments): void
	{
		foreach ($lessonAttachments as $la)
		{
			unset($this->lessonAttachments[$la->getLessonAttachmentId()->toString()]);
		}
	}
}