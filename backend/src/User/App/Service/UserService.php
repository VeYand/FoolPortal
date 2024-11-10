<?php
declare(strict_types=1);

namespace App\User\App\Service;

use App\Common\Exception\AppException;
use App\Common\Transaction\TransactionInterface;
use App\User\Domain\Service\Input\CreateUserInput;
use App\User\Domain\Service\Input\UpdateUserInput;
use App\User\Domain\Service\UserService as DomainUserService;

readonly class UserService
{
	public function __construct(
		private DomainUserService    $domainUserService,
		private TransactionInterface $transaction,
	)
	{
	}

	/**
	 * @throws AppException
	 */
	public function create(CreateUserInput $input): void
	{
		$callback = function () use ($input): void
		{
			$this->domainUserService->create($input);
		};

		$this->transaction->execute($callback);
	}


	/**
	 * @throws AppException
	 */
	public function update(UpdateUserInput $input): void
	{
		$callback = function () use ($input): void
		{
			$this->domainUserService->update($input);
		};

		$this->transaction->execute($callback);
	}

	/**
	 * @throws AppException
	 */
	public function delete(string $userId): void
	{
		$callback = function () use ($userId): void
		{
			$this->domainUserService->delete($userId);
		};

		$this->transaction->execute($callback);
	}
}