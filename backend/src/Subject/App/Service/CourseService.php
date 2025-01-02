<?php
declare(strict_types=1);

namespace App\Subject\App\Service;

use App\Common\Uuid\UuidInterface;
use App\Subject\App\Adapter\UserAdapterInterface;
use App\Subject\App\Exception\AppException;
use App\Subject\App\Service\Input\CreateCourseInput;
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
	 * @param CreateCourseInput[] $inputs
	 * @throws AppException
	 */
	public function create(array $inputs): void // TODO Избавиться от вызова методов в цикле
	{
		foreach ($inputs as $input)
		{
			$this->assertGroupExists($input->groupId);
		}

		$callback = function () use ($inputs): void
		{
			foreach ($inputs as $input)
			{
				$this->courseService->create($input->teacherSubjectId, $input->groupId);
			}
		};

		$this->transactionService->execute($callback);
	}

	/**
	 * @param UuidInterface[] $courseIds
	 * @throws AppException
	 */
	public function delete(array $courseIds): void
	{
		$callback = function () use ($courseIds): void
		{
			$this->courseService->delete($courseIds);
		};

		$this->transactionService->execute($callback);
	}

	/**
	 * @throws AppException
	 */
	private function assertGroupExists(UuidInterface $groupId): void
	{
		$isExists = $this->userAdapter->isGroupExists($groupId);

		if (!$isExists)
		{
			throw new AppException('Group does not exist', AppException::GROUP_NOT_EXISTS);
		}
	}
}