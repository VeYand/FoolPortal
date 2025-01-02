<?php
declare(strict_types=1);

namespace App\Lesson\App\Query\Data;

use App\Common\Uuid\UuidInterface;

readonly class LessonData
{
	/**
	 * @param UuidInterface[] $attachmentIds
	 */
	public function __construct(
		public UuidInterface      $lessonId,
		public \DateTimeInterface $date,
		public int                $startTime,
		public int                $duration,
		public UuidInterface      $courseId,
		public array              $attachmentIds,
		public ?UuidInterface     $locationId,
		public ?string            $description,
	)
	{
	}
}