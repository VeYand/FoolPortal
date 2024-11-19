<?php
declare(strict_types=1);

namespace App\Tests\Unit\Lesson\Infrastructure;

use App\Lesson\Domain\Model\Attachment;
use App\Lesson\Domain\Repository\AttachmentRepositoryInterface;

class AttachmentInMemoryRepository implements AttachmentRepositoryInterface
{
	/** @var array<string, Attachment> */
	private array $attachments = [];

	public function find(string $attachmentId): ?Attachment
	{
		return $this->attachments[$attachmentId] ?? null;
	}

	/**
	 * @inheritDoc
	 */
	public function findByIds(array $attachmentIds): array
	{
		return array_values(array_filter(
			$this->attachments,
			static fn(Attachment $attachment) => in_array($attachment->getAttachmentId(), $attachmentIds, true)
		));
	}

	public function store(Attachment $attachment): string
	{
		$this->attachments[$attachment->getAttachmentId()] = $attachment;
		return $attachment->getAttachmentId();
	}

	public function delete(Attachment $attachment): void
	{
		unset($this->attachments[$attachment->getAttachmentId()]);
	}
}
