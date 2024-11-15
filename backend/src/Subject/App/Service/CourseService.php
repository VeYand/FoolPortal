<?php
declare(strict_types=1);

namespace App\Subject\App\Service;

use App\Subject\App\Adapter\UserAdapterInterface;
use App\Subject\App\Exception\AppException;
use App\Subject\Domain\Service\CourseService as DomainCourseService;

readonly class CourseService
{
	public function __construct(
		private DomainCourseService  $courseService,
		private UserAdapterInterface $userAdapter,
		private TransactionService   $transactionService,
	)
	{
	}

	/**
	 * @throws AppException
	 */
	public function create(string $teacherSubjectId, string $groupId): void
	{
		$this->assertGroupExists($groupId);

		$callback = function () use ($teacherSubjectId, $groupId): void
		{
			$this->courseService->create($teacherSubjectId, $groupId);
		};

		$this->transactionService->execute($callback);
	}

	/**
	 * @throws AppException
	 */
	public function delete(string $courseId): void
	{
		$callback = function () use ($courseId): void
		{
			$this->courseService->delete($courseId);
		};

		$this->transactionService->execute($callback);
	}

	/**
	 * @throws AppException
	 */
	private function assertGroupExists(string $groupId): void
	{
		$isExists = $this->userAdapter->isGroupExists($groupId);

		if (!$isExists)
		{
			throw new AppException('Group does not exist', AppException::GROUP_NOT_EXISTS);
		}
	}
}