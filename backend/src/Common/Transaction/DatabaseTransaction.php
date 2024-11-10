<?php
declare(strict_types=1);

namespace App\Common\Transaction;

use App\Common\Exception\AppException;
use App\Common\Exception\ThrowableInterface;
use Doctrine\ORM\EntityManagerInterface;

class DatabaseTransaction implements TransactionInterface
{
	private EntityManagerInterface $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * @throws AppException
	 */
	public function execute(callable $callback): void
	{
		$this->entityManager->beginTransaction();

		try
		{
			$callback();
			$this->entityManager->flush();
			$this->entityManager->commit();
		}
		catch (ThrowableInterface $exception)
		{
			$this->entityManager->rollback();
			throw new AppException($exception->getMessage(), $exception->getCode(), [], $exception);
		}
	}
}
