<?php
declare(strict_types=1);

namespace App\Lesson\App\Query;

use App\Lesson\App\Query\Data\AttachmentData;

interface AttachmentQueryServiceInterface
{
	/**
	 * @param string[] $attachmentIds
	 * @return AttachmentData[]
	 */
	public function listAttachmentsByIds(array $attachmentIds): array;
}