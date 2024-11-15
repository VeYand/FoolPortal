<?php
declare(strict_types=1);

namespace App\Subject\App\Service;

use App\Common\Transaction\TransactionInterface;
use App\Subject\App\Exception\AppException;

readonly class TransactionService
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