<?php
declare(strict_types=1);

namespace App\Lesson\Infrastructure\Provider;

use App\Lesson\Domain\Model\Lesson;
use App\Lesson\Domain\Provider\LessonProviderInterface;
use App\Lesson\Domain\Repository\LessonReadRepositoryInterface;

readonly class LessonProvider implements LessonProviderInterface
{
	public function __construct(
		private LessonReadRepositoryInterface $lessonReadRepository,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function findLessonIdsByCourseIds(array $courseIds): array
	{
		$lessons = $this->lessonReadRepository->findByCourses($courseIds);

		return array_map(
			static fn(Lesson $lesson) => $lesson->getLessonId(),
			$lessons,
		);
	}
}