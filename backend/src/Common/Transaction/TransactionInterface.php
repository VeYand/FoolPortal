<?php
declare(strict_types=1);

namespace App\Common\Transaction;

interface TransactionInterface
{
	/**
	 * @throws \Exception
	 */
	public function execute(callable $callback): void;
}