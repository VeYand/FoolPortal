<?php
declare(strict_types=1);

namespace App\Subject\Domain\Service\Event;

use App\Common\Uuid\UuidInterface;

readonly class CourseDeletedEvent implements CourseDeletedEventInterface
{
	/**
	 * @param UuidInterface[] $courseIds
	 */
	public function __construct(
		private array $courseIds,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function getCourseIds(): array
	{
		return $this->courseIds;
	}
}