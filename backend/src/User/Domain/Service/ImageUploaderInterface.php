<?php
declare(strict_types=1);

namespace App\User\Domain\Service;

interface ImageUploaderInterface
{
	public function uploadImage(string $base64Data): string;

	public function removeImage(string $path): void;
}