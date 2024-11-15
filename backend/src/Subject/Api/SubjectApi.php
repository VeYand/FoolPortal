<?php
declare(strict_types=1);

namespace App\Subject\Api;

use App\Subject\Api\Exception\ApiException;
use App\Subject\App\Exception\AppException;
use App\Subject\App\Service\CourseService;
use App\Subject\App\Service\SubjectService;
use App\Subject\App\Service\TeacherSubjectService;

readonly class SubjectApi implements SubjectApiInterface
{
	public function __construct(
		private SubjectService        $subjectService,
		private TeacherSubjectService $teacherSubjectService,
		private CourseService         $courseService,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function createSubject(string $subjectName): void
	{
		self::tryExecute(function () use ($subjectName)
		{
			$this->subjectService->create($subjectName);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function updateSubject(string $subjectId, string $subjectName): void
	{
		self::tryExecute(function () use ($subjectId, $subjectName)
		{
			$this->subjectService->update($subjectId, $subjectName);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function deleteSubject(string $subjectId): void
	{
		self::tryExecute(function () use ($subjectId)
		{
			$this->subjectService->delete($subjectId);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function createTeacherSubject(string $teacherId, string $subjectId): void
	{
		self::tryExecute(function () use ($teacherId, $subjectId)
		{
			$this->teacherSubjectService->create($teacherId, $subjectId);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function deleteTeacherSubject(string $teacherSubjectId): void
	{
		self::tryExecute(function () use ($teacherSubjectId)
		{
			$this->teacherSubjectService->delete($teacherSubjectId);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function createCourse(string $teacherSubjectId, string $groupId): void
	{
		self::tryExecute(function () use ($teacherSubjectId, $groupId)
		{
			$this->courseService->create($teacherSubjectId, $groupId);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function deleteCourse(string $courseId): void
	{
		self::tryExecute(function () use ($courseId)
		{
			$this->courseService->delete($courseId);
		});
	}

	/**
	 * @throws ApiException
	 */
	private static function tryExecute(callable $callback): mixed
	{
		try
		{
			return $callback();
		}
		catch (AppException $e)
		{
			throw new ApiException($e->getMessage(), $e->getCode(), $e);
		}
	}
}