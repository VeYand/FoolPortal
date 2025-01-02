<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Service\Input;

readonly class CreateAttachmentInput
{
	public function __construct(
		public string  $originalName,
		public string  $extension,
		public ?string $description,
		public string  $fileData,
	)
	{
	}
}