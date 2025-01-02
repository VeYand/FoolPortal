<?php
declare(strict_types=1);

namespace App\Subject\Domain\Service\Event;

use App\Common\Uuid\UuidInterface;

interface CourseDeletedEventInterface
{
	/**
	 * @return UuidInterface[]
	 */
	public function getCourseIds(): array;
}