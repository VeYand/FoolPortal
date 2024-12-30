<?php
declare(strict_types=1);

namespace App\Lesson\Api;

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
	public function updateLocation(string $locationId, string $locationName): void;

	/**
	 * @throws ApiException
	 */
	public function deleteLocation(string $locationId): void;

	/**
	 * @throws ApiException
	 */
	public function createAttachment(CreateAttachmentInput $input): string;

	/**
	 * @throws ApiException
	 */
	public function deleteAttachment(string $attachmentId): void;

	/**
	 * @throws ApiException
	 */
	public function createLesson(CreateLessonInput $input): string;

	/**
	 * @throws ApiException
	 */
	public function updateLesson(UpdateLessonInput $input): void;

	/**
	 * @throws ApiException
	 */
	public function deleteLesson(string $lessonId): void;

	/**
	 * @throws ApiException
	 */
	public function addAttachmentToLesson(string $lessonId, string $attachmentId): void;

	/**
	 * @throws ApiException
	 */
	public function removeAttachmentFromLesson(string $lessonId, string $attachmentId): void;

	/**
	 * @return LocationData[]
	 */
	public function listLocations(ListLocationsSpec $spec): array;

	/**
	 * @return AttachmentData[]
	 */
	public function listLessonAttachments(string $lessonId): array;

	/**
	 * @return LessonData[]
	 */
	public function listByTimeInterval(\DateTimeInterface $startTime, \DateTimeInterface $endTime): array;
}