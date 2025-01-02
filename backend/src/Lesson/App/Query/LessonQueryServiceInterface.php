<?php
declare(strict_types=1);

namespace App\Lesson\App\Query;

use App\Lesson\App\Query\Data\LessonData;

interface LessonQueryServiceInterface
{
	/**
	 * @return LessonData[]
	 */
	public function listByTimeInterval(\DateTimeInterface $startTime, \DateTimeInterface $endTime): array;
}