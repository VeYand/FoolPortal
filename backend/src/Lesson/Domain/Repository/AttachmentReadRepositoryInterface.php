<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Repository;

use App\Lesson\Domain\Model\Attachment;

interface AttachmentReadRepositoryInterface
{
	public function find(string $attachmentId): ?Attachment;
}