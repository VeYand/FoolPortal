<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Provider;

interface LessonProviderInterface
{
	/**
	 * @param string[] $courseIds
	 * @return string[]
	 */
	public function getLessonIdsByCourseIds(array $courseIds): array;
}