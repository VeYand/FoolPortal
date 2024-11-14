<?php
declare(strict_types=1);

namespace App\Common\Provider;

use RuntimeException;

class EnvironmentProvider implements EnvironmentProviderInterface
{
	private const string USER_IMAGE_UPLOAD_DIRECTORY_KEY = 'USER_IMAGE_UPLOAD_DIRECTORY';
	private const string ATTACHMENT_UPLOAD_DIRECTORY_KEY = 'ATTACHMENT_UPLOAD_DIRECTORY';

	public function getUserImageUploadDirectory(): string
	{
		if (empty($_ENV[self::USER_IMAGE_UPLOAD_DIRECTORY_KEY]))
		{
			throw new RuntimeException('Environment variable USER_UPLOAD_IMAGE_PATH is not set');
		}
		return $_ENV[self::USER_IMAGE_UPLOAD_DIRECTORY_KEY];
	}

	public function getAttachmentUploadDirectory(): string
	{
		if (empty($_ENV[self::ATTACHMENT_UPLOAD_DIRECTORY_KEY]))
		{
			throw new RuntimeException('Environment variable ATTACHMENT_UPLOAD_PATH is not set');
		}
		return $_ENV[self::ATTACHMENT_UPLOAD_DIRECTORY_KEY];
	}
}
