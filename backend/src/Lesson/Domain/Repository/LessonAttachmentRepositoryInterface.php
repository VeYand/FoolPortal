<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Repository;


use App\Common\Uuid\UuidInterface;
use App\Lesson\Domain\Model\LessonAttachment;

interface LessonAttachmentRepositoryInterface extends LessonAttachmentReadRepositoryInterface
{
	public function store(LessonAttachment $lessonAttachment): UuidInterface;

	/**
	 * @param LessonAttachment[] $lessonAttachments
	 */
	public function delete(array $lessonAttachments): void;
}