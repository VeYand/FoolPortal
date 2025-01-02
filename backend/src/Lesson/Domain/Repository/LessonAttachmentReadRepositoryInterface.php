<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Repository;

use App\Common\Uuid\UuidInterface;
use App\Lesson\Domain\Model\LessonAttachment;

interface LessonAttachmentReadRepositoryInterface
{
	public function findByLessonAndAttachment(UuidInterface $lessonId, UuidInterface $attachmentId): ?LessonAttachment;

	/**
	 * @return LessonAttachment[]
	 */
	public function findByAttachment(UuidInterface $attachmentId): array;

	/**
	 * @param UuidInterface[] $lessonIds
	 * @return LessonAttachment[]
	 */
	public function findByLessons(array $lessonIds): array;
}