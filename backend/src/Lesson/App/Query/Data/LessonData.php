<?php
declare(strict_types=1);

namespace App\Lesson\App\Query\Data;

readonly class LessonData
{
	/**
	 * @param string[] $attachmentIds
	 */
	public function __construct(
		public string             $lessonId,
		public \DateTimeInterface $date,
		public int                $startTime,
		public int                $duration,
		public string             $courseId,
		public array              $attachmentIds,
		public ?string            $locationId,
		public ?string            $description,
	)
	{
	}
}