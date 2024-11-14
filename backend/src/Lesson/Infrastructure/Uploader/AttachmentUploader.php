<?php
declare(strict_types=1);

namespace App\Lesson\Infrastructure\Uploader;

use App\Common\Provider\EnvironmentProviderInterface;
use App\Lesson\Domain\Exception\DomainException;
use App\Lesson\Domain\Service\AttachmentUploaderInterface;
use Random\RandomException;

readonly class AttachmentUploader implements AttachmentUploaderInterface
{
	private string $uploadDirectoryPath;

	/**
	 * @throws DomainException
	 */
	public function __construct(
		EnvironmentProviderInterface $environmentProvider,
	)
	{
		$this->uploadDirectoryPath = $environmentProvider->getAttachmentUploadDirectory();
		$this->ensureUploadDirectoryExists();
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

		$attachmentPath = $this->uploadDirectoryPath . '/' . $fileName;

		if (!rename($tempAttachmentPath, $attachmentPath))
		{
			throw new DomainException("Failed to move file to upload directory");
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
	private function ensureUploadDirectoryExists(): void
	{
		if (!is_dir($this->uploadDirectoryPath) && !mkdir($this->uploadDirectoryPath, 0755, true) && !is_dir($this->uploadDirectoryPath))
		{
			throw new DomainException("Cannot create directory " . $this->uploadDirectoryPath);
		}
	}
}
