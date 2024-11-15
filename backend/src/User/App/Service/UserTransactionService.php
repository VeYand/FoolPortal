<?php
declare(strict_types=1);

namespace App\User\App\Service;

use App\Common\Transaction\TransactionInterface;
use App\User\App\Exception\AppException;

readonly class UserTransactionService
{
	public function __construct(
		private TransactionInterface $transaction,
	)
	{
	}

	/**
	 * @param callable $callback
	 * @throws AppException
	 */
	public function execute(callable $callback): void
	{
		try
		{
			$this->transaction->execute($callback);
		}
		catch (\Exception $e)
		{
			throw new AppException($e->getMessage(), $e->getCode(), $e);
		}
	}
}