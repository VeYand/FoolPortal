<?php
declare(strict_types=1);

namespace App\User\Infrastructure\Uploader;

use App\Common\Exception\DomainException;
use App\User\Domain\Service\Exception\ImageUploadException;
use App\User\Domain\Service\ImageUploaderInterface;
use Random\RandomException;

class ImageUploader implements ImageUploaderInterface
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
	 * @throws ImageUploadException
	 * @throws DomainException
	 */
	public function uploadImage(string $base64Data): string
	{
		$imageType = self::getImageTypeFromBase64($base64Data);

		if (is_null($imageType))
		{
			throw new ImageUploadException("Unsupported image format");
		}

		try
		{
			$imageName = bin2hex(random_bytes(16)) . '.' . $imageType;
		}
		catch (RandomException $e)
		{
			throw new DomainException($e->getMessage());
		}

		$imagePath = self::UPLOAD_DIRECTORY . $imageName;

		$base64Data = preg_replace('#^data:image/[^;]+;base64,#', '', $base64Data);
		$decodedData = base64_decode($base64Data, true);

		if ($decodedData === false)
		{
			throw new ImageUploadException("Failed to decode base64 image data");
		}

		if (file_put_contents($imagePath, $decodedData) === false)
		{
			throw new DomainException("Failed to save image");
		}

		return $imagePath;
	}

	public function removeImage(string $path): void
	{
		if (file_exists($path))
		{
			unlink($path);
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

	private static function getImageTypeFromBase64(string $base64Data): ?string
	{
		$prefixes = [
			'data:image/png;base64,' => 'png',
			'data:image/jpeg;base64,' => 'jpeg',
		];

		foreach ($prefixes as $prefix => $extension)
		{
			if (str_starts_with($base64Data, $prefix))
			{
				return $extension;
			}
		}

		return null;
	}
}