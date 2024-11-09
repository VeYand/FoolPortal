<?php
declare(strict_types=1);

namespace App\Subject\Domain\Model;

readonly class TeacherSubject
{
	public function __construct(
		private string $teacherSubjectId,
		private string $teacherId,
		private string $subjectId,
	)
	{
	}

	public function getTeacherSubjectId(): string
	{
		return $this->teacherSubjectId;
	}

	public function getTeacherId(): string
	{
		return $this->teacherId;
	}

	public function getSubjectId(): string
	{
		return $this->subjectId;
	}
}