<?php
declare(strict_types=1);

namespace App\Lesson\Infrastructure\Query;

use App\Lesson\App\Query\Data\LessonData;
use App\Lesson\App\Query\LessonQueryServiceInterface;
use App\Lesson\Domain\Model\Lesson;
use App\Lesson\Domain\Model\LessonAttachment;
use App\Lesson\Domain\Repository\LessonAttachmentReadRepositoryInterface;
use App\Lesson\Domain\Repository\LessonReadRepositoryInterface;

readonly class LessonQueryService implements LessonQueryServiceInterface
{
	public function __construct(
		private LessonReadRepositoryInterface           $lessonReadRepository,
		private LessonAttachmentReadRepositoryInterface $lessonAttachmentReadRepository,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function listByTimeInterval(\DateTimeInterface $startTime, \DateTimeInterface $endTime): array
	{
		$lessons = $this->lessonReadRepository->findByTimeInterval($startTime, $endTime);
		$lessonIds = array_map(static fn(LessonAttachment $lessonAttachment) => $lessonAttachment->getLessonId(), $lessons);
		$attachmentsByLessonMap = $this->getAttachmentsByLessonMap($lessonIds);

		return array_map(
			static function (Lesson $lesson) use ($attachmentsByLessonMap)
			{
				return new LessonData(
					$lesson->getLessonId(),
					$lesson->getDate(),
					$lesson->getStartTime(),
					$lesson->getDuration(),
					$lesson->getCourseId(),
					$attachmentsByLessonMap[$lesson->getLessonId()] ?? [],
					$lesson->getLocationId(),
					$lesson->getDescription(),
				);
			},
			$lessons,
		);
	}

	/**
	 * @param string[] $lessonIds
	 * @return array<string, string[]>
	 */
	private function getAttachmentsByLessonMap(array $lessonIds): array
	{
		$lessonAttachments = $this->lessonAttachmentReadRepository->findByLessons($lessonIds);

		return array_reduce($lessonAttachments, static function ($carry, LessonAttachment $lessonAttachment)
		{
			$carry[$lessonAttachment->getLessonId()][] = $lessonAttachment->getAttachmentId();
			return $carry;
		}, []);
	}
}