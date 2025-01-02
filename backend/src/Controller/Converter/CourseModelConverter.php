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
			'courseId' => $course->courseId->toString(),
			'groupId' => $course->groupId->toString(),
			'teacherSubjectId' => $course->teacherSubjectId->toString(),
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