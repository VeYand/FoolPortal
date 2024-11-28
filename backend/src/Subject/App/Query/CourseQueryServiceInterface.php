<?php
declare(strict_types=1);

namespace App\Subject\App\Query;

use App\Subject\App\Query\Data\CourseData;

interface CourseQueryServiceInterface
{
	/**
	 * @return CourseData[]
	 */
	public function listAllCourses(): array;

	/**
	 * @return CourseData[]
	 */
	public function listCoursesByGroup(string $groupId): array;

	public function isCourseExists(string $courseId): bool;
}