<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Repository;

use App\Common\Uuid\UuidInterface;
use App\Lesson\Domain\Model\Attachment;

interface AttachmentRepositoryInterface extends AttachmentReadRepositoryInterface
{
	public function store(Attachment $attachment): UuidInterface;

	public function delete(Attachment $attachment): void;
}