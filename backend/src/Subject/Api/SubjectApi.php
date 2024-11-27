<?php
declare(strict_types=1);

namespace App\Subject\Api;

use App\Subject\Api\Exception\ApiException;
use App\Subject\App\Exception\AppException;
use App\Subject\App\Service\CourseService;
use App\Subject\App\Service\SubjectService;
use App\Subject\App\Service\TeacherSubjectService;
use App\Subject\Infrastructure\Query\CourseQueryService;
use App\Subject\Infrastructure\Query\SubjectQueryService;
use App\Subject\Infrastructure\Query\TeacherSubjectQueryService;

readonly class SubjectApi implements SubjectApiInterface
{
	public function __construct(
		private SubjectService             $subjectService,
		private TeacherSubjectService      $teacherSubjectService,
		private CourseService              $courseService,
		private SubjectQueryService        $subjectQueryService,
		private TeacherSubjectQueryService $teacherSubjectQueryService,
		private CourseQueryService         $courseQueryService,
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
	public function createTeacherSubjects(array $inputs): void
	{
		self::tryExecute(function () use ($inputs)
		{
			$this->teacherSubjectService->create($inputs);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function deleteTeacherSubjects(array $teacherSubjectIds): void
	{
		self::tryExecute(function () use ($teacherSubjectIds)
		{
			$this->teacherSubjectService->delete($teacherSubjectIds);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function createCourses(array $inputs): void
	{
		self::tryExecute(function () use ($inputs)
		{
			$this->courseService->create($inputs);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function deleteCourses(array $courseIds): void
	{
		self::tryExecute(function () use ($courseIds)
		{
			$this->courseService->delete($courseIds);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function listAllSubjects(): array
	{
		return $this->subjectQueryService->listAllSubjects();
	}

	/**
	 * @inheritDoc
	 */
	public function listAllTeacherSubjects(): array
	{
		return $this->teacherSubjectQueryService->listAllTeacherSubjects();
	}

	/**
	 * @inheritDoc
	 */
	public function listTeacherSubjectsByGroup(string $groupId): array
	{
		return $this->teacherSubjectQueryService->listTeacherSubjectsByGroup($groupId);
	}

	/**
	 * @inheritDoc
	 */
	public function listAllCourses(): array
	{
		return $this->courseQueryService->listAllCourses();
	}

	/**
	 * @inheritDoc
	 */
	public function listCoursesByGroup(string $groupId): array
	{
		return $this->courseQueryService->listCoursesByGroup($groupId);
	}

	public function isCourseExists(string $courseId): bool
	{
		return $this->courseQueryService->isCourseExists($courseId);
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