<?php
declare(strict_types=1);

namespace App\Subject\Domain\Provider;

use App\Common\Uuid\UuidInterface;

interface CourseProviderInterface
{
	/**
	 * @param UuidInterface[] $groupIds
	 * @return UuidInterface[]
	 */
	public function findCourseIdsByGroupIds(array $groupIds): array;
}