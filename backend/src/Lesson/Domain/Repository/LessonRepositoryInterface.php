<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Repository;

use App\Lesson\Domain\Model\Lesson;

interface LessonRepositoryInterface extends LessonReadRepositoryInterface
{
	public function store(Lesson $lesson): string;

	/**
	 * @param Lesson[] $lessons
	 */
	public function storeList(array $lessons): void;

	/**
	 * @param Lesson[] $lessons
	 */
	public function delete(array $lessons): void;
}