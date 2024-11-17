<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Repository;

use App\Lesson\Domain\Model\Lesson;

interface LessonReadRepositoryInterface
{
	public function find(string $lessonId): ?Lesson;

	/**
	 * @param string[] $lessonIds
	 * @return Lesson[]
	 */
	public function findByIds(array $lessonIds): array;

	/**
	 * @return Lesson[]
	 */
	public function findByLocation(string $locationId): array;

	/**
	 * @return Lesson[]
	 */
	public function findByTimeInterval(\DateTimeInterface $startTime, \DateTimeInterface $endTime): array;

	/**
	 * @param string[] $courseIds
	 * @return Lesson[]
	 */
	public function findByCourses(array $courseIds): array;
}