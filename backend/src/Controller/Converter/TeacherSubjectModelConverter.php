<?php
declare(strict_types=1);

namespace App\Controller\Converter;

use App\Subject\App\Query\Data\TeacherSubjectData;
use OpenAPI\Server\Model\TeacherSubjectData as ApiTeacherSubjectData;

readonly class TeacherSubjectModelConverter
{
	public static function convertTeacherSubjectDataToApiTeacherSubject(TeacherSubjectData $teacherSubject): ApiTeacherSubjectData
	{
		return new ApiTeacherSubjectData([
			'teacherSubjectId' => $teacherSubject->teacherSubjectId,
			'teacherId' => $teacherSubject->teacherId,
			'subjectId' => $teacherSubject->subjectId,
		]);
	}

	/**
	 * @param TeacherSubjectData[] $teacherSubjects
	 * @return ApiTeacherSubjectData[]
	 */
	public static function convertTeacherSubjectsToApiTeacherSubjects(array $teacherSubjects): array
	{
		return array_map(
			static fn(TeacherSubjectData $teacherSubject) => self::convertTeacherSubjectDataToApiTeacherSubject($teacherSubject),
			$teacherSubjects,
		);
	}
}