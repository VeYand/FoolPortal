<?php
declare(strict_types=1);

namespace App\Lesson\Infrastructure\Uploader;

use App\Common\Exception\DomainException;
use App\Lesson\Domain\Service\AttachmentUploaderInterface;
use Random\RandomException;
use RuntimeException;

class AttachmentUploader implements AttachmentUploaderInterface
{
	private const string UPLOAD_DIRECTORY = 'upload/';

	/**
	 * @throws DomainException
	 */
	public function __construct()
	{
		self::ensureUploadDirectoryExists();
	}

	/**
	 * @throws DomainException
	 */
	public function uploadAttachment(string $tempAttachmentPath): string
	{
		if (!file_exists($tempAttachmentPath) || !is_readable($tempAttachmentPath))
		{
			throw new DomainException("Temporary attachment file does not exist or is not readable");
		}

		try
		{
			$fileName = bin2hex(random_bytes(16)) . '.' . pathinfo($tempAttachmentPath, PATHINFO_EXTENSION);
		}
		catch (RandomException $e)
		{
			throw new DomainException($e->getMessage());
		}

		$attachmentPath = self::UPLOAD_DIRECTORY . '/' . $fileName;

		if (!rename($tempAttachmentPath, $attachmentPath))
		{
			throw new RuntimeException("Failed to move file to upload directory");
		}

		return $attachmentPath;
	}

	/**
	 * @throws DomainException
	 */
	public function removeAttachment(string $path): void
	{
		if (file_exists($path) && !unlink($path))
		{
			throw new DomainException("Failed to delete attachment at path: $path");
		}
	}


	/**
	 * @throws DomainException
	 */
	private static function ensureUploadDirectoryExists(): void
	{
		if (!is_dir(self::UPLOAD_DIRECTORY) && !mkdir(self::UPLOAD_DIRECTORY, 0755, true) && !is_dir(self::UPLOAD_DIRECTORY))
		{
			throw new DomainException("Cannot create directory " . self::UPLOAD_DIRECTORY);
		}
	}
}
