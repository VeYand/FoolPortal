<?php
declare(strict_types=1);

namespace App\Lesson\App\Query\Data;

readonly class AttachmentData
{
	public function __construct(
		string  $attachmentId,
		string  $name,
		?string $description,
	)
	{
	}
}