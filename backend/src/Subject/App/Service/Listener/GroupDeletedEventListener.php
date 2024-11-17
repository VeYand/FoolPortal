<?php
declare(strict_types=1);

namespace App\Subject\App\Service\Listener;

use App\Subject\App\Exception\AppException;
use App\Subject\App\Service\TransactionService;
use App\Subject\Domain\Service\CourseService;
use App\User\Domain\Service\Event\GroupDeletedEventInterface;

readonly class GroupDeletedEventListener
{
	public function __construct(
		private CourseService      $courseService,
		private TransactionService $transactionService,
	)
	{
	}

	/**
	 * @throws AppException
	 */
	public function __invoke(GroupDeletedEventInterface $event): void
	{
		$callback = function () use ($event): void
		{
			$this->courseService->deleteByGroup($event->getGroupId());
		};

		$this->transactionService->execute($callback);
	}
}