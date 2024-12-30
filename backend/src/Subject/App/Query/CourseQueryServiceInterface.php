<?php
declare(strict_types=1);

namespace App\Subject\App\Query;

use App\Subject\App\Query\Data\CourseData;
use App\Subject\App\Query\Spec\ListCoursesSpec;

interface CourseQueryServiceInterface
{
	/**
	 * @return CourseData[]
	 */
	public function listCourses(ListCoursesSpec $spec): array;

	public function isCourseExists(string $courseId): bool;
}