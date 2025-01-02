<?php
declare(strict_types=1);

namespace App\Lesson\App\Query;

use App\Lesson\App\Adapter\SessionAdapterInterface;
use App\Lesson\App\Adapter\SubjectAdapterInterface;
use App\Lesson\App\Adapter\UserAdapterInterface;
use App\Lesson\App\Query\Data\LessonData;

readonly class AuthorizedLessonQueryService implements LessonQueryServiceInterface
{
	public function __construct(
		private LessonQueryServiceInterface $lessonQueryService,
		private SessionAdapterInterface     $sessionAdapter,
		private UserAdapterInterface        $userAdapter,
		private SubjectAdapterInterface     $subjectAdapter,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function listByTimeInterval(\DateTimeInterface $startTime, \DateTimeInterface $endTime): array
	{
		$lessons = $this->lessonQueryService->listByTimeInterval($startTime, $endTime);

		$currentUserId = $this->sessionAdapter->getCurrentUserId();
		$currentUserGroupIds = $this->userAdapter->listUserGroupIds($currentUserId);
		$currentUserCourseIds = $this->subjectAdapter->listCourseIdsByGroupIds($currentUserGroupIds);

		return array_filter(
			$lessons,
			static fn(LessonData $lesson) => in_array($lesson->courseId, $currentUserCourseIds),
		);
	}
}