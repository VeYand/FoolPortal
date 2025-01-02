<?php
declare(strict_types=1);

namespace App\Lesson\App\Provider;

interface AttachmentProviderInterface
{
	public function getAttachmentData(string $attachmentPath): string;
}