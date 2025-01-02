<?php
declare(strict_types=1);

namespace App\Lesson\App\Query\Data;

use App\Common\Uuid\UuidInterface;

readonly class AttachmentData
{
	public function __construct(
		public UuidInterface $attachmentId,
		public string        $extension,
		public string        $name,
		public ?string       $description,
	)
	{
	}
}