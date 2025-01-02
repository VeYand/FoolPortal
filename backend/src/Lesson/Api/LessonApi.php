<?php
declare(strict_types=1);

namespace App\Lesson\Api;

use App\Common\Uuid\UuidInterface;
use App\Lesson\Api\Exception\ApiException;
use App\Lesson\App\Exception\AppException;
use App\Lesson\App\Query\AttachmentQueryServiceInterface;
use App\Lesson\App\Query\LessonQueryServiceInterface;
use App\Lesson\App\Query\LocationQueryServiceInterface;
use App\Lesson\App\Query\Spec\ListLocationsSpec;
use App\Lesson\App\Service\AttachmentService;
use App\Lesson\App\Service\LessonService;
use App\Lesson\App\Service\LocationService;
use App\Lesson\Domain\Service\Input\CreateAttachmentInput;
use App\Lesson\Domain\Service\Input\CreateLessonInput;
use App\Lesson\Domain\Service\Input\UpdateLessonInput;

readonly class LessonApi implements LessonApiInterface
{
	public function __construct(
		private LocationService                 $locationService,
		private AttachmentService               $attachmentService,
		private LessonService                   $lessonService,
		private LocationQueryServiceInterface   $locationQueryService,
		private AttachmentQueryServiceInterface $attachmentQueryService,
		private LessonQueryServiceInterface     $lessonQueryService,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function createLocation(string $locationName): void
	{
		self::tryExecute(function () use ($locationName)
		{
			$this->locationService->create($locationName);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function updateLocation(UuidInterface $locationId, string $locationName): void
	{
		self::tryExecute(function () use ($locationId, $locationName)
		{
			$this->locationService->update($locationId, $locationName);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function deleteLocation(UuidInterface $locationId): void
	{
		self::tryExecute(function () use ($locationId)
		{
			$this->locationService->delete($locationId);
		});
	}

	/**
	 * @throws ApiException
	 */
	public function getAttachmentData(UuidInterface $attachmentId): string
	{
		return self::tryExecute(function () use ($attachmentId)
		{
			return $this->attachmentQueryService->getAttachmentData($attachmentId);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function createAttachment(CreateAttachmentInput $input): UuidInterface
	{
		return self::tryExecute(function () use ($input)
		{
			return $this->attachmentService->create($input);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function deleteAttachment(UuidInterface $attachmentId): void
	{
		self::tryExecute(function () use ($attachmentId)
		{
			$this->attachmentService->delete($attachmentId);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function createLesson(CreateLessonInput $input): UuidInterface
	{
		return self::tryExecute(function () use ($input)
		{
			return $this->lessonService->create($input);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function updateLesson(UpdateLessonInput $input): void
	{
		self::tryExecute(function () use ($input)
		{
			$this->lessonService->update($input);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function deleteLesson(UuidInterface $lessonId): void
	{
		self::tryExecute(function () use ($lessonId)
		{
			$this->lessonService->delete($lessonId);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function addAttachmentToLesson(UuidInterface $lessonId, UuidInterface $attachmentId): void
	{
		self::tryExecute(function () use ($lessonId, $attachmentId)
		{
			$this->lessonService->addAttachmentToLesson($lessonId, $attachmentId);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function removeAttachmentFromLesson(UuidInterface $lessonId, UuidInterface $attachmentId): void
	{
		self::tryExecute(function () use ($lessonId, $attachmentId)
		{
			$this->lessonService->removeAttachmentFromLesson($lessonId, $attachmentId);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function listLocations(ListLocationsSpec $spec): array
	{
		return $this->locationQueryService->listLocations($spec);
	}

	/**
	 * @inheritDoc
	 */
	public function listLessonAttachments(UuidInterface $lessonId): array
	{
		return $this->attachmentQueryService->listLessonAttachments($lessonId);
	}

	/**
	 * @inheritDoc
	 */
	public function listByTimeInterval(\DateTimeInterface $startTime, \DateTimeInterface $endTime): array
	{
		return $this->lessonQueryService->listByTimeInterval($startTime, $endTime);
	}

	/**
	 * @throws ApiException
	 */
	private static function tryExecute(callable $callback): mixed
	{
		try
		{
			return $callback();
		}
		catch (AppException $e)
		{
			throw new ApiException($e->getMessage(), $e->getCode(), $e);
		}
	}
}