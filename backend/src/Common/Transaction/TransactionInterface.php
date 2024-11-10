<?php
declare(strict_types=1);

namespace App\Common\Transaction;

use App\Common\Exception\AppException;

interface TransactionInterface
{
	/**
	 * @throws AppException
	 */
	public function execute(callable $callback): void;
}