<?php
declare(strict_types=1);

namespace App\Controller\Converter;

use App\Subject\App\Query\Data\CourseData;
use OpenAPI\Server\Model\CourseData as ApiCourseData;

readonly class CourseModelConverter
{
	public static function convertCourseDataToApiCourse(CourseData $course): ApiCourseData
	{
		return new ApiCourseData([
			'courseId' => $course->courseId,
			'groupId' => $course->groupId,
			'teacherSubjectId' => $course->teacherSubjectId,
		]);
	}

	/**
	 * @param CourseData[] $courses
	 * @return ApiCourseData[]
	 */
	public static function convertCoursesToApiCourses(array $courses): array
	{
		return array_map(
			static fn(CourseData $course) => self::convertCourseDataToApiCourse($course),
			$courses,
		);
	}
}