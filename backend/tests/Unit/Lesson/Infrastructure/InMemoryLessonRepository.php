<?php
declare(strict_types=1);

namespace App\Tests\Unit\Lesson\Infrastructure;

use App\Common\Uuid\UuidInterface;
use App\Lesson\Domain\Model\Lesson;
use App\Lesson\Domain\Repository\LessonRepositoryInterface;

class InMemoryLessonRepository implements LessonRepositoryInterface
{
	/** @var array<string, Lesson> */
	private array $lessons = [];

	public function find(UuidInterface $lessonId): ?Lesson
	{
		return $this->lessons[$lessonId->toString()] ?? null;
	}

	/**
	 * @inheritDoc
	 */
	public function findByIds(array $lessonIds): array
	{
		/** @var Lesson[] $result */
		$result = [];
		foreach ($lessonIds as $id) {
			$found = $this->find($id);
			if ($found !== null) {
				$result[] = $found;
			}
		}
		return $result;
	}

	/**
	 * @inheritDoc
	 */
	public function findByLocation(UuidInterface $locationId): array
	{
		throw new \BadMethodCallException('Not implemented');
	}

	/**
	 * @inheritDoc
	 */
	public function findByCourses(array $courseIds): array
	{
		throw new \BadMethodCallException('Not implemented');
	}

	public function store(Lesson $lesson): UuidInterface
	{
		$this->lessons[$lesson->getLessonId()->toString()] = $lesson;
		return $lesson->getLessonId();
	}

	/**
	 * @inheritDoc
	 */
	public function storeList(array $lessons): void
	{
		throw new \BadMethodCallException('Not implemented');
	}

	/**
	 * @inheritDoc
	 */
	public function delete(array $lessons): void
	{
		throw new \BadMethodCallException('Not implemented');
	}
}