<?php
declare(strict_types=1);

namespace App\Subject\Domain\Repository;

use App\Subject\Domain\Model\Course;

interface CourseRepositoryInterface extends CourseReadRepositoryInterface
{
	public function store(Course $course): string;

	public function delete(Course $course): void;
}