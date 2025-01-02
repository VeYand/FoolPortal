<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Service;

use App\Common\Uuid\UuidInterface;
use App\Common\Uuid\UuidProviderInterface;
use App\Lesson\Domain\Exception\DomainException;
use App\Lesson\Domain\Model\LessonAttachment;
use App\Lesson\Domain\Repository\AttachmentReadRepositoryInterface;
use App\Lesson\Domain\Repository\LessonAttachmentRepositoryInterface;
use App\Lesson\Domain\Repository\LessonReadRepositoryInterface;

readonly class LessonAttachmentService
{
	public function __construct(
		private LessonAttachmentRepositoryInterface $lessonAttachmentRepository,
		private AttachmentReadRepositoryInterface   $attachmentReadRepository,
		private LessonReadRepositoryInterface       $lessonReadRepository,
		private UuidProviderInterface               $uuidProvider,
	)
	{
	}

	/**
	 * @throws DomainException
	 */
	public function addAttachmentToLesson(UuidInterface $lessonId, UuidInterface $attachmentId): UuidInterface
	{
		$this->assertLessonAttachmentNotExists($lessonId, $attachmentId);
		$this->assertLessonExists($lessonId);
		$this->assertAttachmentExists($attachmentId);

		$lessonAttachment = new LessonAttachment(
			$this->uuidProvider->generate(),
			$lessonId,
			$attachmentId,
		);

		return $this->lessonAttachmentRepository->store($lessonAttachment);
	}

	public function removeAttachmentFromLesson(UuidInterface $lessonId, UuidInterface $attachmentId): void
	{
		$lessonAttachment = $this->lessonAttachmentRepository->findByLessonAndAttachment($lessonId, $attachmentId);

		if (!is_null($lessonAttachment))
		{
			$this->lessonAttachmentRepository->delete($lessonAttachment->getLessonAttachmentId());
		}
	}

	/**
	 * @throws DomainException
	 */
	private function assertAttachmentExists(UuidInterface $attachmentId): void
	{
		$attachment = $this->attachmentReadRepository->find($attachmentId);

		if (is_null($attachment))
		{
			throw new DomainException('Attachment not found', DomainException::ATTACHMENT_NOT_FOUND);
		}
	}

	/**
	 * @throws DomainException
	 */
	private function assertLessonExists(UuidInterface $lessonId): void
	{
		$lesson = $this->lessonReadRepository->find($lessonId);

		if (is_null($lesson))
		{
			throw new DomainException('Lesson not found', DomainException::LESSON_NOT_FOUND);
		}
	}

	/**
	 * @throws DomainException
	 */
	private function assertLessonAttachmentNotExists(UuidInterface $lessonId, UuidInterface $attachmentId): void
	{
		$lessonAttachment = $this->lessonAttachmentRepository->findByLessonAndAttachment($lessonId, $attachmentId);

		if (!is_null($lessonAttachment))
		{
			throw new DomainException('Lesson attachment already exists', DomainException::LESSON_ATTACHMENT_ALREADY_EXISTS);
		}
	}
}