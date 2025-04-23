<?php
declare(strict_types=1);

namespace App\Tests\Unit\Lesson\Infrastructure;

use App\Common\Uuid\UuidInterface;
use App\Lesson\Domain\Model\Attachment;
use App\Lesson\Domain\Repository\AttachmentRepositoryInterface;

class InMemoryAttachmentRepository implements AttachmentRepositoryInterface
{
	/** @var array<string, Attachment> */
	private array $attachments = [];

	public function find(UuidInterface $attachmentId): ?Attachment
	{
		return $this->attachments[$attachmentId->toString()] ?? null;
	}

	/**
	 * @inheritDoc
	 */
	public function findByIds(array $attachmentIds): array
	{
		/** @var Attachment[] $result */
		$result = [];
		foreach ($attachmentIds as $id)
		{
			$found = $this->find($id);
			if ($found !== null)
			{
				$result[] = $found;
			}
		}
		return $result;
	}

	public function store(Attachment $attachment): UuidInterface
	{
		$this->attachments[$attachment->getAttachmentId()->toString()] = $attachment;
		return $attachment->getAttachmentId();
	}

	public function delete(Attachment $attachment): void
	{
		throw new \BadMethodCallException('Not implemented');
	}
}