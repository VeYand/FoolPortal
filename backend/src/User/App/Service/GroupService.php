<?php
declare(strict_types=1);

namespace App\User\App\Service;

use App\Common\Uuid\UuidInterface;
use App\User\App\Exception\AppException;
use App\User\Domain\Service\GroupService as DomainGroupService;

readonly class GroupService
{
	public function __construct(
		private DomainGroupService     $domainGroupService,
		private UserTransactionService $transactionService,
	)
	{
	}

	/**
	 * @throws AppException
	 */
	public function create(string $groupName): UuidInterface
	{
		/** @var UuidInterface $createdGroupId */
		$createdGroupId = null;
		$callback = function () use ($groupName, &$createdGroupId): void
		{
			$createdGroupId = $this->domainGroupService->create($groupName);
		};

		$this->transactionService->execute($callback);
		return $createdGroupId;
	}


	/**
	 * @throws AppException
	 */
	public function update(UuidInterface $groupId, string $groupName): void
	{
		$callback = function () use ($groupId, $groupName): void
		{
			$this->domainGroupService->update($groupId, $groupName);
		};

		$this->transactionService->execute($callback);
	}

	/**
	 * @throws AppException
	 */
	public function delete(UuidInterface $groupId): void
	{
		$callback = function () use ($groupId): void
		{
			$this->domainGroupService->delete($groupId);
		};

		$this->transactionService->execute($callback);
	}
}