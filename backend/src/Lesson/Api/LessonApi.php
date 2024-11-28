<?php
declare(strict_types=1);

namespace App\Lesson\Api;

use App\Lesson\Api\Exception\ApiException;
use App\Lesson\App\Exception\AppException;
use App\Lesson\App\Query\AttachmentQueryServiceInterface;
use App\Lesson\App\Query\LessonQueryServiceInterface;
use App\Lesson\App\Query\LocationQueryServiceInterface;
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
	public function updateLocation(string $locationId, string $locationName): void
	{
		self::tryExecute(function () use ($locationId, $locationName)
		{
			$this->locationService->update($locationId, $locationName);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function deleteLocation(string $locationId): void
	{
		self::tryExecute(function () use ($locationId)
		{
			$this->locationService->delete($locationId);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function createAttachment(CreateAttachmentInput $input): string
	{
		return self::tryExecute(function () use ($input)
		{
			return $this->attachmentService->create($input);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function deleteAttachment(string $attachmentId): void
	{
		self::tryExecute(function () use ($attachmentId)
		{
			$this->attachmentService->delete($attachmentId);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function createLesson(CreateLessonInput $input): string
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
	public function deleteLesson(string $lessonId): void
	{
		self::tryExecute(function () use ($lessonId)
		{
			$this->lessonService->delete($lessonId);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function addAttachmentToLesson(string $lessonId, string $attachmentId): void
	{
		self::tryExecute(function () use ($lessonId, $attachmentId)
		{
			$this->lessonService->addAttachmentToLesson($lessonId, $attachmentId);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function removeAttachmentFromLesson(string $lessonId, string $attachmentId): void
	{
		self::tryExecute(function () use ($lessonId, $attachmentId)
		{
			$this->lessonService->removeAttachmentFromLesson($lessonId, $attachmentId);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function findLocationsByIds(array $locationIds): array
	{
		return $this->locationQueryService->findLocationsByIds($locationIds);
	}

	/**
	 * @inheritDoc
	 */
	public function listAllLocations(): array
	{
		return $this->locationQueryService->listAllLocations();
	}

	/**
	 * @inheritDoc
	 */
	public function listAttachmentsByIds(array $attachmentIds): array
	{
		return $this->attachmentQueryService->listAttachmentsByIds($attachmentIds);
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