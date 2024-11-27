<?php
declare(strict_types=1);

namespace App\Subject\App\Service;

use App\Subject\App\Adapter\Data\UserRole;
use App\Subject\App\Adapter\UserAdapterInterface;
use App\Subject\App\Exception\AppException;
use App\Subject\App\Service\Input\CreateTeacherSubjectInput;
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
	 * @param CreateTeacherSubjectInput[] $inputs
	 * @throws AppException
	 */
	public function create(array $inputs): void // TODO Убрать вызов методов в цикле
	{
		foreach ($inputs as $input)
		{
			$this->assertIsTeacher($input->teacherId);
		}

		$callback = function () use ($inputs): void
		{
			foreach ($inputs as $input)
			{
				$this->teacherSubjectService->create($input->teacherId, $input->subjectId);
			}
		};

		$this->transactionService->execute($callback);
	}

	/**
	 * @param string[] $teacherSubjectIds
	 * @throws AppException
	 */
	public function delete(array $teacherSubjectIds): void
	{
		$callback = function () use ($teacherSubjectIds): void
		{
			$this->teacherSubjectService->delete($teacherSubjectIds);
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
			throw new AppException('User does not exist', AppException::USER_NOT_EXISTS);
		}

		if ($user->role !== UserRole::TEACHER)
		{
			throw new AppException('User is not a teacher', AppException::USER_IS_NOT_TEACHER);
		}
	}
}