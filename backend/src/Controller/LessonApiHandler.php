<?php
declare(strict_types=1);

namespace App\Controller;

use App\Common\Uuid\UuidProviderInterface;
use App\Controller\Converter\AttachmentModelConverter;
use App\Controller\Converter\LessonModelConverter;
use App\Controller\Converter\LocationModelConverter;
use App\Controller\Exception\ExceptionHandler;
use App\Lesson\Api\LessonApiInterface;
use App\Lesson\App\Query\Spec\ListLocationsSpec;
use App\Lesson\Domain\Service\Input\CreateAttachmentInput;
use OpenAPI\Server\Api\LessonApiInterface as LessonApiHandlerInterface;
use OpenAPI\Server\Model\AddAttachmentToLessonRequest;
use OpenAPI\Server\Model\AttachmentData;
use OpenAPI\Server\Model\CreateAttachment200Response as ApiCreateAttachment200Response;
use OpenAPI\Server\Model\CreateLesson200Response as ApiCreateLesson200Response;
use OpenAPI\Server\Model\CreateLessonRequest;
use OpenAPI\Server\Model\CreateLocationRequest;
use OpenAPI\Server\Model\DeleteAttachmentFromLessonRequest;
use OpenAPI\Server\Model\DeleteAttachmentRequest;
use OpenAPI\Server\Model\DeleteLessonRequest;
use OpenAPI\Server\Model\DeleteLocationRequest;
use OpenAPI\Server\Model\ListLessonAttachmentRequest;
use OpenAPI\Server\Model\ListLessonAttachments200Response;
use OpenAPI\Server\Model\ListLessonsRequest;
use OpenAPI\Server\Model\ListLocationsRequest;
use OpenAPI\Server\Model\UpdateLessonRequest;
use OpenAPI\Server\Model\UpdateLocationRequest;
use OpenAPI\Server\Model\EmptyResponse as ApiEmptyResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class LessonApiHandler implements LessonApiHandlerInterface
{
	public function __construct(
		private LessonApiInterface    $lessonApi,
		private ExceptionHandler      $exceptionHandler,
		private UuidProviderInterface $uuidProvider,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function createLocation(CreateLocationRequest $createLocationRequest, int &$responseCode, array &$responseHeaders): array|null|object
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($createLocationRequest)
		{
			$this->lessonApi->createLocation($createLocationRequest->getName());
			return new ApiEmptyResponse();
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function deleteLocation(DeleteLocationRequest $deleteLocationRequest, int &$responseCode, array &$responseHeaders): array|null|object
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($deleteLocationRequest)
		{
			$this->lessonApi->deleteLocation(
				$this->uuidProvider->fromStringToUuid($deleteLocationRequest->getLocationId()),
			);
			return new ApiEmptyResponse();
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function listLocations(ListLocationsRequest $listLocationsRequest, int &$responseCode, array &$responseHeaders): array|object|null
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($listLocationsRequest)
		{
			$locations = $this->lessonApi->listLocations(
				new ListLocationsSpec(
					$this->uuidProvider->fromStringsToUuids($listLocationsRequest->getLocationIds()),
				),
			);

			return LocationModelConverter::convertAppLocationsToApiLocations($locations);
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function updateLocation(UpdateLocationRequest $updateLocationRequest, int &$responseCode, array &$responseHeaders): array|null|object
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($updateLocationRequest)
		{
			$this->lessonApi->updateLocation(
				$this->uuidProvider->fromStringToUuid($updateLocationRequest->getLocationId()),
				$updateLocationRequest->getName(),
			);
			return new ApiEmptyResponse();
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function createAttachment(AttachmentData $attachment, int &$responseCode, array &$responseHeaders): array|object|null
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($attachment)
		{
			$file = $attachment->getFile();
			if (!$file instanceof UploadedFile)
			{
				return new \RuntimeException();
			}

			$attachmentId = $this->lessonApi->createAttachment(
				new CreateAttachmentInput(
					$attachment->getName(),
					$file->getExtension(),
					$attachment->getDescription(),
					$file->getPath(),
				),
			);
			return new ApiCreateAttachment200Response([
				'attachmentId' => $attachmentId->toString(),
			]);
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function deleteAttachment(DeleteAttachmentRequest $deleteAttachmentRequest, int &$responseCode, array &$responseHeaders): array|object|null
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($deleteAttachmentRequest)
		{
			$this->lessonApi->deleteAttachment(
				$this->uuidProvider->fromStringToUuid($deleteAttachmentRequest->getAttachmentId()),
			);
			return new ApiEmptyResponse();
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function listLessons(ListLessonsRequest $listLessonsRequest, int &$responseCode, array &$responseHeaders): array|object|null
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($listLessonsRequest)
		{
			$lessons = $this->lessonApi->listByTimeInterval($listLessonsRequest->getStartTime(), $listLessonsRequest->getEndTime());
			return LessonModelConverter::convertAppLessonsToApiLessons($lessons);
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function createLesson(CreateLessonRequest $createLessonRequest, int &$responseCode, array &$responseHeaders): array|object|null
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($createLessonRequest)
		{
			$lessonId = $this->lessonApi->createLesson(
				LessonModelConverter::convertCreateLessonRequestToCreateLessonInput($createLessonRequest, $this->uuidProvider),
			);
			return new ApiCreateLesson200Response([
				'lessonId' => $lessonId->toString(),
			]);
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function updateLesson(UpdateLessonRequest $updateLessonRequest, int &$responseCode, array &$responseHeaders): array|object|null
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($updateLessonRequest)
		{
			$this->lessonApi->updateLesson(
				LessonModelConverter::convertUpdateLessonRequestToUpdateLessonInput($updateLessonRequest, $this->uuidProvider),
			);
			return new ApiEmptyResponse();
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function deleteLesson(DeleteLessonRequest $deleteLessonRequest, int &$responseCode, array &$responseHeaders): array|object|null
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($deleteLessonRequest)
		{
			$this->lessonApi->deleteLesson(
				$this->uuidProvider->fromStringToUuid($deleteLessonRequest->getLessonId()),
			);
			return new ApiEmptyResponse();
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function addAttachmentToLesson(AddAttachmentToLessonRequest $addAttachmentToLessonRequest, int &$responseCode, array &$responseHeaders): array|object|null
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($addAttachmentToLessonRequest)
		{
			$this->lessonApi->addAttachmentToLesson(
				$this->uuidProvider->fromStringToUuid($addAttachmentToLessonRequest->getLessonId()),
				$this->uuidProvider->fromStringToUuid($addAttachmentToLessonRequest->getAttachmentId()),
			);
			return new ApiEmptyResponse();
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function deleteAttachmentFromLesson(DeleteAttachmentFromLessonRequest $deleteAttachmentFromLessonRequest, int &$responseCode, array &$responseHeaders): array|object|null
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($deleteAttachmentFromLessonRequest)
		{
			$this->lessonApi->removeAttachmentFromLesson(
				$this->uuidProvider->fromStringToUuid($deleteAttachmentFromLessonRequest->getLessonId()),
				$this->uuidProvider->fromStringToUuid($deleteAttachmentFromLessonRequest->getAttachmentId()),
			);
			return new ApiEmptyResponse();
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function listLessonAttachments(ListLessonAttachmentRequest $listLessonAttachmentRequest, int &$responseCode, array &$responseHeaders): array|object|null
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($listLessonAttachmentRequest)
		{
			$attachments = $this->lessonApi->listLessonAttachments(
				$this->uuidProvider->fromStringToUuid($listLessonAttachmentRequest->getLessonId()),
			);

			return new ListLessonAttachments200Response([
				'attachments' => AttachmentModelConverter::convertAppAttachmentsToApiAttachments($attachments),
			]);
		}, $responseCode, $responseHeaders);
	}
}