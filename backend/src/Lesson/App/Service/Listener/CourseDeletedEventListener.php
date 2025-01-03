<?php
declare(strict_types=1);

namespace App\Lesson\App\Service\Listener;

use App\Lesson\Domain\Provider\LessonProviderInterface;
use App\Lesson\Domain\Service\LessonService;
use App\Subject\App\Exception\AppException;
use App\Subject\App\Service\TransactionService;
use App\Subject\Domain\Service\Event\CourseDeletedEventInterface;

readonly class CourseDeletedEventListener
{
	public function __construct(
		private LessonService           $lessonService,
		private LessonProviderInterface $lessonProvider,
		private TransactionService      $transactionService,
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
			$lessonIds = $this->lessonProvider->findLessonIdsByCourseIds($event->getCourseIds());
			$this->lessonService->delete($lessonIds);
		};

		$this->transactionService->execute($callback);
	}
}