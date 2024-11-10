<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Repository;

use App\Lesson\Domain\Model\LessonAttachment;

interface LessonAttachmentReadRepositoryInterface
{
	public function find(string $lessonAttachmentId): ?LessonAttachment;

	public function findByLessonAndAttachment(string $lessonId, string $attachmentId): ?LessonAttachment;
}