<?php
declare(strict_types=1);

namespace App\Subject\Domain\Service\Event;

interface CourseDeletedEventInterface
{
	/**
	 * @return string[]
	 */
	public function getCourseIds(): array;
}