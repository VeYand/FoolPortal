<?php
declare(strict_types=1);

namespace App\Lesson\App\Service;

use App\Lesson\App\Adapter\SubjectAdapterInterface;
use App\Lesson\App\Exception\AppException;
use App\Lesson\Domain\Service\LessonService as DomainLessonService;
use App\Lesson\Domain\Service\LessonAttachmentService as DomainLessonAttachmentService;
use App\Lesson\Domain\Service\Input\CreateLessonInput;
use App\Lesson\Domain\Service\Input\UpdateLessonInput;

readonly class LessonService
{
	public function __construct(
		private DomainLessonService           $lessonService,
		private DomainLessonAttachmentService $lessonAttachmentService,
		private SubjectAdapterInterface       $subjectAdapter,
		private TransactionService            $transactionService,
	)
	{
	}

	/**
	 * @throws AppException
	 */
	public function create(CreateLessonInput $input): string
	{
		$this->assertCourseExists($input->courseId);

		$lessonId = '';
		$callback = function () use ($input, &$lessonId): void
		{
			$lessonId = $this->lessonService->create($input);
		};

		$this->transactionService->execute($callback);
		return $lessonId;
	}

	/**
	 * @throws AppException
	 */
	public function update(UpdateLessonInput $input): void
	{
		if (!is_null($input->courseId))
		{
			$this->assertCourseExists($input->courseId);
		}

		$callback = function () use ($input): void
		{
			$this->lessonService->update($input);
		};

		$this->transactionService->execute($callback);
	}

	/**
	 * @throws AppException
	 */
	public function delete(string $lessonId): void
	{
		$callback = function () use ($lessonId): void
		{
			$this->lessonService->delete($lessonId);
		};

		$this->transactionService->execute($callback);
	}

	/**
	 * @throws AppException
	 */
	public function addAttachmentToLesson(string $lessonId, string $attachmentId): void
	{
		$callback = function () use ($lessonId, $attachmentId): void
		{
			$this->lessonAttachmentService->addAttachmentToLesson($lessonId, $attachmentId);
		};

		$this->transactionService->execute($callback);
	}

	/**
	 * @throws AppException
	 */
	public function removeAttachmentFromLesson(string $lessonId, string $attachmentId): void
	{
		$callback = function () use ($lessonId, $attachmentId): void
		{
			$this->lessonAttachmentService->removeAttachmentFromLesson($lessonId, $attachmentId);
		};

		$this->transactionService->execute($callback);
	}

	/**
	 * @throws AppException
	 */
	private function assertCourseExists(string $courseId): void
	{
		$isExists = $this->subjectAdapter->isCourseExists($courseId);

		if (!$isExists)
		{
			throw new AppException('Course not found', AppException::COURSE_NOT_EXISTS);
		}
	}
}