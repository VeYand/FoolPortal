<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Provider;

interface LessonProviderInterface
{
	/**
	 * @param string[] $courseIds
	 * @return string[]
	 */
	public function findLessonIdsByCourseIds(array $courseIds): array;
}