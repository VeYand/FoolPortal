<?php
declare(strict_types=1);

namespace App\Lesson\App\Query;

use App\Common\Uuid\UuidInterface;
use App\Lesson\App\Query\Data\AttachmentData;

interface AttachmentQueryServiceInterface
{
	/**
	 * @return AttachmentData[]
	 */
	public function listLessonAttachments(UuidInterface $lessonId): array;
}