<?php
declare(strict_types=1);

namespace App\Lesson\App\Adapter;

use App\Common\Uuid\UuidInterface;

interface SubjectAdapterInterface
{
	public function isCourseExists(UuidInterface $courseId): bool;

	/**
	 * @param UuidInterface[] $groupIds
	 * @return UuidInterface[]
	 */
	public function listCourseIdsByGroupIds(array $groupIds): array;
}