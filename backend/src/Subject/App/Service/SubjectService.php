<?php
declare(strict_types=1);

namespace App\Subject\App\Service;

use App\Common\Uuid\UuidInterface;
use App\Subject\App\Exception\AppException;
use App\Subject\Domain\Service\SubjectService as DomainSubjectService;

readonly class SubjectService
{
	public function __construct(
		private DomainSubjectService $subjectService,
		private TransactionService   $transactionService,
	)
	{
	}

	/**
	 * @throws AppException
	 */
	public function create(string $subjectName): void
	{
		$callback = function () use ($subjectName): void
		{
			$this->subjectService->create($subjectName);
		};

		$this->transactionService->execute($callback);
	}

	/**
	 * @throws AppException
	 */
	public function update(UuidInterface $subjectId, string $subjectName): void
	{
		$callback = function () use ($subjectId, $subjectName): void
		{
			$this->subjectService->update($subjectId, $subjectName);
		};

		$this->transactionService->execute($callback);
	}

	/**
	 * @throws AppException
	 */
	public function delete(UuidInterface $subjectId): void
	{
		$callback = function () use ($subjectId): void
		{
			$this->subjectService->delete($subjectId);
		};

		$this->transactionService->execute($callback);
	}
}