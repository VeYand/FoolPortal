<?php
declare(strict_types=1);

namespace App\Lesson\Api;

use App\Common\Uuid\UuidInterface;
use App\Lesson\Api\Exception\ApiException;
use App\Lesson\App\Query\Data\AttachmentData;
use App\Lesson\App\Query\Data\LessonData;
use App\Lesson\App\Query\Data\LocationData;
use App\Lesson\App\Query\Spec\ListLocationsSpec;
use App\Lesson\Domain\Service\Input\CreateAttachmentInput;
use App\Lesson\Domain\Service\Input\CreateLessonInput;
use App\Lesson\Domain\Service\Input\UpdateLessonInput;

interface LessonApiInterface
{
	/**
	 * @throws ApiException
	 */
	public function createLocation(string $locationName): void;

	/**
	 * @throws ApiException
	 */
	public function updateLocation(UuidInterface $locationId, string $locationName): void;

	/**
	 * @throws ApiException
	 */
	public function deleteLocation(UuidInterface $locationId): void;

	/**
	 * @throws ApiException
	 */
	public function getAttachmentData(UuidInterface $attachmentId): string;

	/**
	 * @throws ApiException
	 */
	public function createAttachment(CreateAttachmentInput $input): UuidInterface;

	/**
	 * @throws ApiException
	 */
	public function deleteAttachment(UuidInterface $attachmentId): void;

	/**
	 * @throws ApiException
	 */
	public function createLesson(CreateLessonInput $input): UuidInterface;

	/**
	 * @throws ApiException
	 */
	public function updateLesson(UpdateLessonInput $input): void;

	/**
	 * @throws ApiException
	 */
	public function deleteLesson(UuidInterface $lessonId): void;

	/**
	 * @throws ApiException
	 */
	public function addAttachmentToLesson(UuidInterface $lessonId, UuidInterface $attachmentId): void;

	/**
	 * @throws ApiException
	 */
	public function removeAttachmentFromLesson(UuidInterface $lessonId, UuidInterface $attachmentId): void;

	/**
	 * @return LocationData[]
	 */
	public function listLocations(ListLocationsSpec $spec): array;

	/**
	 * @return AttachmentData[]
	 */
	public function listLessonAttachments(UuidInterface $lessonId): array;

	/**
	 * @return LessonData[]
	 */
	public function listByTimeInterval(\DateTimeInterface $startTime, \DateTimeInterface $endTime): array;
}