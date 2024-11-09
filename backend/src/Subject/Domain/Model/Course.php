<?php
declare(strict_types=1);

namespace App\Subject\Domain\Model;

readonly class Course
{
	public function __construct(
		private string $courseId,
		private string $teacherSubjectId,
		private string $groupId,
	)
	{
	}

	public function getCourseId(): string
	{
		return $this->courseId;
	}

	public function getTeacherSubjectId(): string
	{
		return $this->teacherSubjectId;
	}

	public function getGroupId(): string
	{
		return $this->groupId;
	}
}