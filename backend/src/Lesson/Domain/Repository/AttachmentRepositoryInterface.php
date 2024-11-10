<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Repository;

use App\Lesson\Domain\Model\Attachment;

interface AttachmentRepositoryInterface extends AttachmentReadRepositoryInterface
{
	public function store(Attachment $attachment): string;

	public function delete(Attachment $attachment): void;
}