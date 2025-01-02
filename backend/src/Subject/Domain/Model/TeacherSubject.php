<?php
declare(strict_types=1);

namespace App\Subject\Domain\Model;

use App\Common\Uuid\UuidInterface;

readonly class TeacherSubject
{
	public function __construct(
		private UuidInterface $teacherSubjectId,
		private UuidInterface $teacherId,
		private UuidInterface $subjectId,
	)
	{
	}

	public function getTeacherSubjectId(): UuidInterface
	{
		return $this->teacherSubjectId;
	}

	public function getTeacherId(): UuidInterface
	{
		return $this->teacherId;
	}

	public function getSubjectId(): UuidInterface
	{
		return $this->subjectId;
	}
}