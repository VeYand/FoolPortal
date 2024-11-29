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
	 * TODO Проверить, что это действительно студенты
	 * TODO Избавиться от запросов в цикле
	 *
	 * @param string[] $groupIds
	 * @param string[] $userIds
	 * @throws AppException
	 */
	public function createGroupMembers(array $groupIds, array $userIds): void
	{
		$callback = function () use ($groupIds, $userIds): void
		{
			foreach ($groupIds as $groupId)
			{
				foreach ($userIds as $userId)
				{
					$this->groupMemberService->createGroupMembers($groupId, $userId);
				}
			}
		};

		$this->transactionService->execute($callback);
	}

	/**
	 * TODO Проверить, что это действительно студенты
	 * TODO Избавиться от запросов в цикле
	 *
	 * @param string[] $groupIds
	 * @param string[] $userIds
	 * @throws AppException
	 */
	public function deleteGroupMembers(array $groupIds, array $userIds): void
	{
		$callback = function () use ($groupIds, $userIds): void
		{
			foreach ($groupIds as $groupId)
			{
				foreach ($userIds as $userId)
				{
					$this->groupMemberService->deleteGroupMembers($groupId, $userId);
				}
			}
		};

		$this->transactionService->execute($callback);
	}
}