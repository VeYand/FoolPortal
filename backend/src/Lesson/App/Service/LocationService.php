<?php
declare(strict_types=1);

namespace App\Lesson\App\Service;

use App\Lesson\App\Exception\AppException;
use App\Lesson\Domain\Service\LocationService as DomainLocationService;

readonly class LocationService
{
	public function __construct(
		private DomainLocationService $locationService,
		private TransactionService    $transactionService,
	)
	{
	}

	/**
	 * @throws AppException
	 */
	public function create(string $locationName): void
	{
		$callback = function () use ($locationName): void
		{
			$this->locationService->create($locationName);
		};

		$this->transactionService->execute($callback);
	}

	/**
	 * @throws AppException
	 */
	public function update(string $locationId, string $locationName): void
	{
		$callback = function () use ($locationId, $locationName): void
		{
			$this->locationService->update($locationId, $locationName);
		};

		$this->transactionService->execute($callback);
	}

	/**
	 * @throws AppException
	 */
	public function delete(string $locationId): void
	{
		$callback = function () use ($locationId): void
		{
			$this->locationService->delete($locationId);
		};

		$this->transactionService->execute($callback);
	}
}