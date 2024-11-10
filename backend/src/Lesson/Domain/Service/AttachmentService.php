<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Service;

use App\Common\Exception\DomainException;
use App\Common\Uuid\UuidProviderInterface;
use App\Lesson\Domain\Model\Attachment;
use App\Lesson\Domain\Repository\AttachmentRepositoryInterface;
use App\Lesson\Domain\Service\Input\CreateAttachmentInput;

readonly class AttachmentService
{
	public function __construct(
		private AttachmentRepositoryInterface $attachmentRepository,
		private AttachmentUploaderInterface   $attachmentUploader,
		private UuidProviderInterface         $uuidProvider,
	)
	{
	}

	/**
	 * @throws DomainException
	 */
	public function create(CreateAttachmentInput $input): string
	{
		$attachmentPath = $this->attachmentUploader->uploadAttachment($input->tempPath);

		$attachment = new Attachment(
			$this->uuidProvider->generate(),
			$input->originalName,
			$input->description,
			$attachmentPath,
			$input->extension,
		);

		return $this->attachmentRepository->store($attachment);
	}

	/**
	 * @throws DomainException
	 */
	public function delete(string $attachmentId): void
	{
		$attachment = $this->attachmentRepository->find($attachmentId);

		if (!is_null($attachment))
		{
			$this->attachmentUploader->removeAttachment($attachment->getPath());
			$this->attachmentRepository->delete($attachment);
		}
	}
}