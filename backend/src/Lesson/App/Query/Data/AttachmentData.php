<?php
declare(strict_types=1);

namespace App\Lesson\App\Query\Data;

readonly class AttachmentData
{
	public function __construct(
		public string  $attachmentId,
		public string  $extension,
		public string  $name,
		public ?string $description,
	)
	{
	}
}