<?php
declare(strict_types=1);

namespace App\Tests\Unit\Lesson\Infrastructure;

use App\Lesson\Domain\Model\Lesson;
use App\Lesson\Domain\Repository\LessonRepositoryInterface;

class LessonInMemoryRepository implements LessonRepositoryInterface
{
	/** @var array<string, Lesson> */
	private array $lessons = [];

	public function find(string $lessonId): ?Lesson
	{
		return $this->lessons[$lessonId] ?? null;
	}

	/**
	 * @inheritDoc
	 */
	public function findByIds(array $lessonIds): array
	{
		return array_values(array_filter(
			$this->lessons,
			static fn(Lesson $lesson) => in_array($lesson->getLessonId(), $lessonIds, true),
		));
	}

	/**
	 * @inheritDoc
	 */
	public function findByLocation(string $locationId): array
	{
		return array_values(array_filter(
			$this->lessons,
			static fn(Lesson $lesson) => $lesson->getLocationId() === $locationId,
		));
	}

	/**
	 * @inheritDoc
	 */
	public function findByTimeInterval(\DateTimeInterface $startTime, \DateTimeInterface $endTime): array
	{
		return array_values(array_filter(
			$this->lessons,
			static function (Lesson $lesson) use ($startTime, $endTime)
			{
				$lessonStart = $lesson->getDate()->getTimestamp() + $lesson->getStartTime();
				$lessonEnd = $lessonStart + $lesson->getDuration();

				$intervalStart = $startTime->getTimestamp();
				$intervalEnd = $endTime->getTimestamp();

				return $lessonStart < $intervalEnd && $lessonEnd > $intervalStart;
			},
		));
	}

	/**
	 * @inheritDoc
	 */
	public function findByCourses(array $courseIds): array
	{
		return array_values(array_filter(
			$this->lessons,
			static fn(Lesson $lesson) => in_array($lesson->getCourseId(), $courseIds, true),
		));
	}

	public function store(Lesson $lesson): string
	{
		$this->lessons[$lesson->getLessonId()] = $lesson;
		return $lesson->getLessonId();
	}

	public function storeList(array $lessons): void
	{
		foreach ($lessons as $lesson)
		{
			if (!$lesson instanceof Lesson)
			{
				throw new \InvalidArgumentException('All items must be instances of Lesson.');
			}
			$this->store($lesson);
		}
	}

	public function delete(Lesson $lesson): void
	{
		unset($this->lessons[$lesson->getLessonId()]);
	}
}
