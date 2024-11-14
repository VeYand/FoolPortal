<?php
declare(strict_types=1);

namespace App\Common\Transaction;

use Doctrine\ORM\EntityManagerInterface;

class DatabaseTransaction implements TransactionInterface
{
	private EntityManagerInterface $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * @throws \Exception
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
		catch (\Exception $e)
		{
			$this->entityManager->rollback();
			throw $e;
		}
	}
}
