<?php
declare(strict_types=1);

namespace App\Subject\Domain\Provider;

interface TeacherSubjectProviderInterface
{
	/**
	 * @param string[] $teacherIds
	 * @return string[]
	 */
	public function findTeacherSubjectIdsByTeacherIds(array $teacherIds): array;
}