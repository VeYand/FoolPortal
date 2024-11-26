<?php
declare(strict_types=1);

namespace App\Controller\Converter;


use OpenAPI\Server\Model\SubjectData as ApiSubjectData;
use App\Subject\App\Query\Data\SubjectData;

readonly class SubjectModelConverter
{
	public static function convertSubjectDataToApiSubject(SubjectData $subject): ApiSubjectData
	{
		return new ApiSubjectData([
			'subjectId' => $subject->id,
			'name' => $subject->name,
		]);
	}

	/**
	 * @param SubjectData[] $subjects
	 * @return ApiSubjectData[]
	 */
	public static function convertSubjectsListToApiSubjects(array $subjects): array
	{
		return array_map(
			static fn(SubjectData $subject) => self::convertSubjectDataToApiSubject($subject),
			$subjects,
		);
	}
}