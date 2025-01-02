<?php
declare(strict_types=1);

namespace App\Lesson\App\Service;

use App\Common\Uuid\UuidInterface;
use App\Lesson\App\Exception\AppException;
use App\Lesson\Domain\Service\AttachmentService as DomainAttachmentService;
use App\Lesson\Domain\Service\Input\CreateAttachmentInput;

readonly class AttachmentService
{
	public function __construct(
		private DomainAttachmentService $attachmentService,
		private TransactionService      $transactionService,
	)
	{
	}

	/**
	 * @throws AppException
	 */
	public function create(CreateAttachmentInput $input): UuidInterface
	{
		/** @var UuidInterface $attachmentId */
		$attachmentId = null;
		$callback = function () use ($input, &$attachmentId): void
		{
			$attachmentId = $this->attachmentService->create($input);
		};

		$this->transactionService->execute($callback);
		return $attachmentId;
	}

	/**
	 * @throws AppException
	 */
	public function delete(UuidInterface $attachmentId): void
	{
		$callback = function () use ($attachmentId): void
		{
			$this->attachmentService->delete($attachmentId);
		};

		$this->transactionService->execute($callback);
	}
}