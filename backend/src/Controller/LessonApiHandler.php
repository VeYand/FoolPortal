<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Exception\ExceptionHandler;
use App\Lesson\Api\LessonApiInterface;
use OpenAPI\Server\Api\LessonApiInterface as LessonApiHandlerInterface;
use OpenAPI\Server\Model\CreateLocationRequest;
use OpenAPI\Server\Model\DeleteSubjectRequest;
use OpenAPI\Server\Model\UpdateLocationRequest;

readonly class LessonApiHandler implements LessonApiHandlerInterface
{
	public function __construct(
		private LessonApiInterface $subjectApi,
		private ExceptionHandler   $exceptionHandler,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function createLocation(CreateLocationRequest $createLocationRequest, int &$responseCode, array &$responseHeaders): void
	{
		$this->exceptionHandler->executeWithHandle(function () use ($createLocationRequest)
		{
			$this->subjectApi->createLocation($createLocationRequest->getName());
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function deleteLocation(DeleteSubjectRequest $deleteSubjectRequest, int &$responseCode, array &$responseHeaders): void
	{
		$this->exceptionHandler->executeWithHandle(function () use ($deleteSubjectRequest)
		{
			$this->subjectApi->deleteLocation($deleteSubjectRequest->getSubjectId());
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function listLocations(int &$responseCode, array &$responseHeaders): array|object|null
	{
		return $this->exceptionHandler->executeWithHandle(function ()
		{
			return $this->subjectApi->listAllLocations();
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function updateLocation(UpdateLocationRequest $updateLocationRequest, int &$responseCode, array &$responseHeaders): void
	{
		$this->exceptionHandler->executeWithHandle(function () use ($updateLocationRequest)
		{
			$this->subjectApi->updateLocation($updateLocationRequest->getLocationId(), $updateLocationRequest->getName());
		}, $responseCode, $responseHeaders);
	}
}