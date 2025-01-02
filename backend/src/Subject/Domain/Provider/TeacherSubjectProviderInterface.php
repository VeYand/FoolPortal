<?php
declare(strict_types=1);

namespace App\Subject\Domain\Provider;

use App\Common\Uuid\UuidInterface;

interface TeacherSubjectProviderInterface
{
	/**
	 * @param UuidInterface[] $teacherIds
	 * @return UuidInterface[]
	 */
	public function findTeacherSubjectIdsByTeacherIds(array $teacherIds): array;
}