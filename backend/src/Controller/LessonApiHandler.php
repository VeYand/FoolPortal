<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Exception\ExceptionHandler;
use App\Lesson\Api\LessonApiInterface;
use OpenAPI\Server\Api\LessonApiInterface as LessonApiHandlerInterface;
use OpenAPI\Server\Model\CreateLocationRequest;
use OpenAPI\Server\Model\DeleteLocationRequest;
use OpenAPI\Server\Model\UpdateLocationRequest;
use OpenAPI\Server\Model\LocationsList as ApiLocationsList;

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
	public function deleteLocation(DeleteLocationRequest $deleteLocationRequest, int &$responseCode, array &$responseHeaders): void
	{
		$this->exceptionHandler->executeWithHandle(function () use ($deleteLocationRequest)
		{
			$this->subjectApi->deleteLocation($deleteLocationRequest->getLocationId());
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function listLocations(int &$responseCode, array &$responseHeaders): array|object|null
	{
		return $this->exceptionHandler->executeWithHandle(function ()
		{
			return new ApiLocationsList([
				'locations' => $this->subjectApi->listAllLocations(),
			]);
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