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
}