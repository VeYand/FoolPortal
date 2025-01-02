<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Provider;

use App\Common\Uuid\UuidInterface;

interface LessonProviderInterface
{
	/**
	 * @param UuidInterface[] $courseIds
	 * @return UuidInterface[]
	 */
	public function findLessonIdsByCourseIds(array $courseIds): array;
}