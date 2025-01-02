<?php
declare(strict_types=1);

namespace App\Tests\Unit\User\Domain\Infrastructure;

use App\User\Domain\Service\ImageUploaderInterface;

class MockImageUploader implements ImageUploaderInterface
{
	/**
	 * @inheritDoc
	 */
	public function uploadImage(string $base64Data): string
	{
		// TODO: Implement uploadImage() method.
		return '';
	}

	/**
	 * @inheritDoc
	 */
	public function removeImage(?string $path): void
	{
		// TODO: Implement removeImage() method.
	}
}