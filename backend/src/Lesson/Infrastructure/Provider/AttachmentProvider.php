<?php
declare(strict_types=1);

namespace App\Lesson\Infrastructure\Provider;

use App\Lesson\App\Provider\AttachmentProviderInterface;
use RuntimeException;

class AttachmentProvider implements AttachmentProviderInterface
{
	public function getAttachmentData(string $attachmentPath): string
	{
		if (!file_exists($attachmentPath))
		{
			throw new RuntimeException("File not found: " . $attachmentPath);
		}

		if (!is_file($attachmentPath))
		{
			throw new RuntimeException("The provided path is not a file: " . $attachmentPath);
		}

		$fileContents = file_get_contents($attachmentPath);
		if ($fileContents === false)
		{
			throw new RuntimeException("Unable to read file: " . $attachmentPath);
		}

		return base64_encode($fileContents);
	}
}
