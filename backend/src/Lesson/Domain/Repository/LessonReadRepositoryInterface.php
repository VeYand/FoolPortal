<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Repository;

use App\Common\Uuid\UuidInterface;
use App\Lesson\Domain\Model\Lesson;

interface LessonReadRepositoryInterface
{
	public function find(UuidInterface $lessonId): ?Lesson;

	/**
	 * @param UuidInterface[] $lessonIds
	 * @return Lesson[]
	 */
	public function findByIds(array $lessonIds): array;

	/**
	 * @return Lesson[]
	 */
	public function findByLocation(UuidInterface $locationId): array;

	/**
	 * @param UuidInterface[] $courseIds
	 * @return Lesson[]
	 */
	public function findByCourses(array $courseIds): array;
}