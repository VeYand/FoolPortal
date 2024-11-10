<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Repository;

use App\Lesson\Domain\Model\Lesson;

interface LessonRepositoryInterface extends LessonReadRepositoryInterface
{
	/**
	 * @param Lesson[] $lessons
	 */
	public function store(array $lessons): void;

	public function delete(Lesson $lesson): void;
}