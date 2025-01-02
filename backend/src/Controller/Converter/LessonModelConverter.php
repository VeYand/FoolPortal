<?php
declare(strict_types=1);

namespace App\Controller\Converter;

use App\Common\Uuid\UuidProviderInterface;
use App\Lesson\App\Query\Data\LessonData;
use App\Lesson\Domain\Service\Input\CreateLessonInput;
use App\Lesson\Domain\Service\Input\UpdateLessonInput;
use OpenAPI\Server\Model\LessonData as ApiLessonData;
use OpenAPI\Server\Model\LessonsList as ApiLessonsList;
use OpenAPI\Server\Model\UpdateLessonRequest as ApiUpdateLessonRequest;
use OpenAPI\Server\Model\CreateLessonRequest as ApiCreateLessonRequest;


readonly class LessonModelConverter
{
	public static function convertCreateLessonRequestToCreateLessonInput(ApiCreateLessonRequest $request, UuidProviderInterface $uuidProvider): CreateLessonInput
	{
		return new CreateLessonInput(
			$request->getDate(),
			$request->getStartTime(),
			$request->getDuration(),
			$uuidProvider->fromStringToUuid($request->getCourseId()),
			$uuidProvider->fromStringToUuid($request->getLocationId()),
			$request->getDescription(),
		);
	}

	public static function convertUpdateLessonRequestToUpdateLessonInput(ApiUpdateLessonRequest $request, UuidProviderInterface $uuidProvider): UpdateLessonInput
	{
		return new UpdateLessonInput(
			$uuidProvider->fromStringToUuid($request->getLessonId()),
			$request->getDate(),
			$request->getStartTime(),
			$request->getDuration(),
			$uuidProvider->fromStringToUuid($request->getCourseId()),
			$uuidProvider->fromStringToUuid($request->getLocationId()),
			$request->getDescription(),
		);
	}

	/**
	 * @param LessonData[] $lessons
	 */
	public static function convertAppLessonsToApiLessons(array $lessons): ApiLessonsList
	{
		$apiLessons = array_map(
			static fn(LessonData $lesson) => self::convertAppLessonToApiLesson($lesson),
			$lessons,
		);

		return new ApiLessonsList([
			'lessons' => $apiLessons,
		]);
	}

	public static function convertAppLessonToApiLesson(LessonData $lesson): ApiLessonData
	{
		return new ApiLessonData([
			'lessonId' => $lesson->lessonId->toString(),
			'date' => $lesson->date,
			'startTime' => $lesson->startTime,
			'duration' => $lesson->duration,
			'courseId' => $lesson->courseId->toString(),
			'locationId' => $lesson->locationId->toString(),
			'description' => $lesson->description,
		]);
	}
}