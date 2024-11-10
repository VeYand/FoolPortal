<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Service\Input;

readonly class CreateLessonInput
{
	public function __construct(
		public \DateTimeInterface $date,
		public int                $startTime,
		public int                $duration,
		public string             $courseId,
		public ?string            $locationId,
		public ?string            $description,
	)
	{
	}
}