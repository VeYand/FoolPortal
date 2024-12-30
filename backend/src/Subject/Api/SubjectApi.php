<?php
declare(strict_types=1);

namespace App\Subject\Api;

use App\Subject\Api\Exception\ApiException;
use App\Subject\App\Exception\AppException;
use App\Subject\App\Query\CourseQueryServiceInterface;
use App\Subject\App\Query\Spec\ListTeacherSubjectsSpec;
use App\Subject\App\Query\SubjectQueryServiceInterface;
use App\Subject\App\Query\TeacherSubjectQueryServiceInterface;
use App\Subject\App\Service\CourseService;
use App\Subject\App\Service\SubjectService;
use App\Subject\App\Service\TeacherSubjectService;

readonly class SubjectApi implements SubjectApiInterface
{
	public function __construct(
		private SubjectService                      $subjectService,
		private TeacherSubjectService               $teacherSubjectService,
		private CourseService                       $courseService,
		private SubjectQueryServiceInterface        $subjectQueryService,
		private TeacherSubjectQueryServiceInterface $teacherSubjectQueryService,
		private CourseQueryServiceInterface         $courseQueryService,
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
	public function listTeacherSubjects(ListTeacherSubjectsSpec $spec): array
	{
		return $this->teacherSubjectQueryService->listTeacherSubjects($spec);
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