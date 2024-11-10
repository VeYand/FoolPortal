<?php
declare(strict_types=1);

namespace App\User\App\Service;

use App\Common\Exception\AppException;
use App\Common\Transaction\TransactionInterface;
use App\User\Domain\Service\GroupMemberService as DomainGroupMemberService;

readonly class GroupMemberService
{
	public function __construct(
		private DomainGroupMemberService $groupMemberService,
		private TransactionInterface     $transaction,
	)
	{
	}

	/**
	 * @throws AppException
	 */
	public function addUserToGroup(string $groupId, string $userId): void
	{
		$callback = function () use ($groupId, $userId): void
		{
			$this->groupMemberService->addUserToGroup($groupId, $userId);
		};

		$this->transaction->execute($callback);
	}

	/**
	 * @throws AppException
	 */
	public function removeUserFromGroup(string $groupId, string $userId): void
	{
		$callback = function () use ($groupId, $userId): void
		{
			$this->groupMemberService->removeUserFromGroup($groupId, $userId);
		};

		$this->transaction->execute($callback);
	}
}