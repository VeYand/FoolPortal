<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Service\Input;

use App\Common\Uuid\UuidInterface;

readonly class UpdateLessonInput
{
	public function __construct(
		public UuidInterface       $lessonId,
		public ?\DateTimeInterface $date,
		public ?int                $startTime,
		public ?int                $duration,
		public ?UuidInterface      $courseId,
		public ?UuidInterface      $locationId,
		public ?string             $description,
	)
	{
	}
}