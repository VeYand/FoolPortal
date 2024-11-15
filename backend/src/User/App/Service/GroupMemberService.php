<?php
declare(strict_types=1);

namespace App\User\App\Service;

use App\User\App\Exception\AppException;
use App\User\Domain\Service\GroupMemberService as DomainGroupMemberService;

readonly class GroupMemberService
{
	public function __construct(
		private DomainGroupMemberService $groupMemberService,
		private UserTransactionService   $transactionService,
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

		$this->transactionService->execute($callback);
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

		$this->transactionService->execute($callback);
	}
}