<?php
declare(strict_types=1);

namespace App\Subject\Domain\Provider;

interface CourseProviderInterface
{
	/**
	 * @param string[] $groupIds
	 * @return string[]
	 */
	public function findCourseIdsByGroupIds(array $groupIds): array;
}