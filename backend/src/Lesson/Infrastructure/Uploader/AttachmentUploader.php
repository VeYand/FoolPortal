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
	public function uploadAttachment(string $base64Data): string
	{
		try
		{
			$fileName = bin2hex(random_bytes(16));
		}
		catch (RandomException $e)
		{
			throw new DomainException($e->getMessage());
		}

		$filePath = $this->uploadDirectoryPath . '/' . $fileName;

		$decodedData = base64_decode($base64Data, true);

		if ($decodedData === false)
		{
			throw new DomainException("Failed to decode base64 file", DomainException::INVALID_BASE_64_DATA);
		}

		if (file_put_contents($filePath, $decodedData) === false)
		{
			throw new DomainException("Failed to save file");
		}

		return $filePath;
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
