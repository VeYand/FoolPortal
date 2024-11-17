<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Repository;

use App\Lesson\Domain\Model\LessonAttachment;

interface LessonAttachmentReadRepositoryInterface
{
	public function findByLessonAndAttachment(string $lessonId, string $attachmentId): ?LessonAttachment;

	/**
	 * @return LessonAttachment[]
	 */
	public function findByAttachment(string $attachmentId): array;

	/**
	 * @param string[] $lessonIds
	 * @return LessonAttachment[]
	 */
	public function findByLessons(array $lessonIds): array;
}