<?php
declare(strict_types=1);

namespace App\User\App\Service;

use App\Common\Exception\AppException;
use App\Common\Transaction\TransactionInterface;
use App\User\Domain\Service\GroupService as DomainGroupService;

readonly class GroupService
{
	public function __construct(
		private DomainGroupService   $domainGroupService,
		private TransactionInterface $transaction,
	)
	{
	}

	/**
	 * @throws AppException
	 */
	public function create(string $groupName): void
	{
		$callback = function () use ($groupName): void
		{
			$this->domainGroupService->create($groupName);
		};

		$this->transaction->execute($callback);
	}


	/**
	 * @throws AppException
	 */
	public function update(string $groupId, string $groupName): void
	{
		$callback = function () use ($groupId, $groupName): void
		{
			$this->domainGroupService->update($groupId, $groupName);
		};

		$this->transaction->execute($callback);
	}

	/**
	 * @throws AppException
	 */
	public function delete(string $groupId): void
	{
		$callback = function () use ($groupId): void
		{
			$this->domainGroupService->delete($groupId);
		};

		$this->transaction->execute($callback);
	}
}