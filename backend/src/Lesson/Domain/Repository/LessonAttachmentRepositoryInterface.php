<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Repository;


use App\Lesson\Domain\Model\LessonAttachment;

interface LessonAttachmentRepositoryInterface extends LessonAttachmentReadRepositoryInterface
{
	public function store(LessonAttachment $lessonAttachment): string;

	public function delete(LessonAttachment $lessonAttachment): void;
}