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
	 * @param string[] $studentIds
	 * @throws AppException
	 */
	public function addStudentToGroup(string $groupId, array $studentIds): void
	{
		$callback = function () use ($groupId, $studentIds): void
		{
			foreach ($studentIds as $studentId)
			{
				$this->groupMemberService->addStudentToGroup($groupId, $studentId);
			}
		};

		$this->transactionService->execute($callback);
	}

	/**
	 * TODO Проверить, что это действительно студенты
	 * TODO Избавиться от запросов в цикле
	 *
	 * @param string[] $studentIds
	 * @throws AppException
	 */
	public function removeStudentFromGroup(string $groupId, array $studentIds): void
	{
		$callback = function () use ($groupId, $studentIds): void
		{
			foreach ($studentIds as $studentId)
			{
				$this->groupMemberService->removeStudentFromGroup($groupId, $studentId);
			}
		};

		$this->transactionService->execute($callback);
	}
}