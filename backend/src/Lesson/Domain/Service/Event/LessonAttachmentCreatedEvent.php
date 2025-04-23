<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Service\Event;

use App\Common\Uuid\UuidInterface;

readonly class LessonAttachmentCreatedEvent implements LessonAttachmentCreatedEventInterface
{
	/**
	 * @param UuidInterface[] $lessonAttachmentIds
	 */
	public function __construct(
		private array $lessonAttachmentIds,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function getLessonAttachmentIds(): array
	{
		return $this->lessonAttachmentIds;
	}
}