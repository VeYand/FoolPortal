<?php
declare(strict_types=1);

namespace App\Subject\App\Service\Listener;

use App\Subject\App\Exception\AppException;
use App\Subject\Domain\Service\TeacherSubjectService;
use App\Subject\App\Service\TransactionService;
use App\Subject\Infrastructure\Provider\TeacherSubjectProvider;
use App\User\Domain\Service\Event\UserDeletedEventInterface;

readonly class UserDeletedEventListener
{
	public function __construct(
		private TeacherSubjectService  $teacherSubjectService,
		private TransactionService     $transactionService,
		private TeacherSubjectProvider $teacherSubjectProvider,
	)
	{
	}

	/**
	 * @throws AppException
	 */
	public function __invoke(UserDeletedEventInterface $event): void
	{
		$callback = function () use ($event): void
		{
			$teacherSubjectIds = $this->teacherSubjectProvider->findTeacherSubjectIdsByTeacherIds($event->getUserIds());
			$this->teacherSubjectService->delete($teacherSubjectIds);
		};

		$this->transactionService->execute($callback);
	}
}