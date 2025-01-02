<?php
declare(strict_types=1);

namespace App\Lesson\App\Adapter;

interface SubjectAdapterInterface
{
	public function isCourseExists(string $courseId): bool;

	/**
	 * @param string[] $groupIds
	 * @return string[]
	 */
	public function listCourseIdsByGroupIds(array $groupIds): array;
}