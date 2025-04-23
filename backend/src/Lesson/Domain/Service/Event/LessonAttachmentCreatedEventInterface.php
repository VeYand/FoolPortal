<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Service\Event;

use App\Common\Uuid\UuidInterface;

interface LessonAttachmentCreatedEventInterface
{
	/**
	 * @return UuidInterface[]
	 */
	public function getLessonAttachmentIds(): array;
}