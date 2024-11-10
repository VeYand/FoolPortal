<?php
declare(strict_types=1);

namespace App\User\Domain\Service;

use App\Common\Exception\DomainException;

interface ImageUploaderInterface
{
	/**
	 * @throws DomainException
	 */
	public function uploadImage(string $base64Data): string;

	/**
	 * @throws DomainException
	 */
	public function removeImage(string $path): void;
}