<?php
declare(strict_types=1);

namespace App\Lesson\App\Service\Listener;

use App\Lesson\Domain\Service\LessonService;
use App\Subject\App\Exception\AppException;
use App\Subject\App\Service\TransactionService;
use App\Subject\Domain\Service\Event\CourseDeletedEventInterface;

readonly class CourseDeletedEventListener
{
	public function __construct(
		private LessonService      $lessonService,
		private TransactionService $transactionService,
	)
	{
	}

	/**
	 * @throws AppException
	 */
	public function __invoke(CourseDeletedEventInterface $event): void
	{
		$callback = function () use ($event): void
		{
			$this->lessonService->deleteByCourses($event->getCourseIds());
		};

		$this->transactionService->execute($callback);
	}
}