<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Service;

use App\Lesson\Domain\Exception\DomainException;

interface AttachmentUploaderInterface
{
	/**
	 * @throws DomainException
	 */
	public function uploadAttachment(string $base64Data): string;

	/**
	 * @throws DomainException
	 */
	public function removeAttachment(string $path): void;
}