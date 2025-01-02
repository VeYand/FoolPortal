<?php
declare(strict_types=1);

namespace App\Subject\Domain\Model;

use App\Common\Uuid\UuidInterface;

readonly class Course
{
	public function __construct(
		private UuidInterface $courseId,
		private UuidInterface $teacherSubjectId,
		private UuidInterface $groupId,
	)
	{
	}

	public function getCourseId(): UuidInterface
	{
		return $this->courseId;
	}

	public function getTeacherSubjectId(): UuidInterface
	{
		return $this->teacherSubjectId;
	}

	public function getGroupId(): UuidInterface
	{
		return $this->groupId;
	}
}