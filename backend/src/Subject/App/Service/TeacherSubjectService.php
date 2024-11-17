<?php
declare(strict_types=1);

namespace App\Subject\App\Service;

use App\Subject\App\Adapter\Data\UserRole;
use App\Subject\App\Adapter\UserAdapterInterface;
use App\Subject\App\Exception\AppException;
use App\Subject\Domain\Service\TeacherSubjectService as DomainTeacherSubjectService;

readonly class TeacherSubjectService
{
	public function __construct(
		private DomainTeacherSubjectService $teacherSubjectService,
		private UserAdapterInterface        $userAdapter,
		private TransactionService          $transactionService,
	)
	{
	}

	/**
	 * @throws AppException
	 */
	public function create(string $teacherId, string $subjectId): void
	{
		$this->assertIsTeacher($teacherId);

		$callback = function () use ($teacherId, $subjectId): void
		{
			$this->teacherSubjectService->create($teacherId, $subjectId);
		};

		$this->transactionService->execute($callback);
	}

	/**
	 * @throws AppException
	 */
	public function delete(string $teacherSubjectId): void
	{
		$callback = function () use ($teacherSubjectId): void
		{
			$this->teacherSubjectService->delete($teacherSubjectId);
		};

		$this->transactionService->execute($callback);
	}

	/**
	 * @throws AppException
	 */
	private function assertIsTeacher(string $teacherId): void
	{
		$user = $this->userAdapter->getUser($teacherId);

		if (is_null($user))
		{
			throw new AppException('User does not exist', AppException::GROUP_NOT_EXISTS);
		}

		if ($user->role !== UserRole::TEACHER)
		{
			throw new AppException('User is not a teacher', AppException::USER_IS_NOT_TEACHER);
		}
	}
}