<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Converter\CourseModelConverter;
use App\Controller\Converter\SubjectModelConverter;
use App\Controller\Converter\TeacherSubjectModelConverter;
use App\Controller\Exception\ExceptionHandler;
use App\Subject\Api\SubjectApiInterface;
use OpenAPI\Server\Api\SubjectApiInterface as SubjectApiHandlerInterface;
use OpenAPI\Server\Model\CreateCourseInput as ApiCreateCourseInput;
use App\Subject\App\Service\Input\CreateCourseInput;
use OpenAPI\Server\Model\CreateCoursesRequest as ApiCreateCoursesRequest;
use OpenAPI\Server\Model\CreateSubjectRequest;
use App\Subject\App\Service\Input\CreateTeacherSubjectInput;
use OpenAPI\Server\Model\CreateTeacherSubjectInput as ApiCreateTeacherSubjectInput;
use OpenAPI\Server\Model\CreateTeacherSubjectsRequest;
use OpenAPI\Server\Model\DeleteCoursesRequest;
use OpenAPI\Server\Model\DeleteSubjectRequest;
use OpenAPI\Server\Model\DeleteTeacherSubjectsRequest;
use OpenAPI\Server\Model\SubjectsList as ApiSubjectsList;
use OpenAPI\Server\Model\CoursesList as ApiCoursesList;
use OpenAPI\Server\Model\TeacherSubjectsList as ApiTeacherSubjectsList;
use OpenAPI\Server\Model\UpdateSubjectRequest;

readonly class SubjectApiHandler implements SubjectApiHandlerInterface
{
	public function __construct(
		private SubjectApiInterface $subjectApi,
		private ExceptionHandler    $exceptionHandler,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function createSubject(CreateSubjectRequest $createSubjectRequest, int &$responseCode, array &$responseHeaders): void
	{
		$this->exceptionHandler->executeWithHandle(function () use ($createSubjectRequest)
		{
			$this->subjectApi->createSubject($createSubjectRequest->getName());
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function listSubjects(int &$responseCode, array &$responseHeaders): array|object|null
	{
		return $this->exceptionHandler->executeWithHandle(function ()
		{
			$subjects = $this->subjectApi->listAllSubjects();

			return new ApiSubjectsList([
				'subjects' => SubjectModelConverter::convertSubjectsListToApiSubjects($subjects),
			]);
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function updateSubject(UpdateSubjectRequest $updateSubjectRequest, int &$responseCode, array &$responseHeaders): void
	{
		$this->exceptionHandler->executeWithHandle(function () use ($updateSubjectRequest)
		{
			$this->subjectApi->updateSubject($updateSubjectRequest->getSubjectId(), $updateSubjectRequest->getName());
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function deleteSubject(DeleteSubjectRequest $deleteSubjectRequest, int &$responseCode, array &$responseHeaders): void
	{
		$this->exceptionHandler->executeWithHandle(function () use ($deleteSubjectRequest)
		{
			$this->subjectApi->deleteSubject($deleteSubjectRequest->getSubjectId());
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function createTeacherSubjects(CreateTeacherSubjectsRequest $createTeacherSubjectsRequest, int &$responseCode, array &$responseHeaders): void
	{
		$this->exceptionHandler->executeWithHandle(function () use ($createTeacherSubjectsRequest)
		{
			$this->subjectApi->createTeacherSubjects(
				array_map(
					static fn(ApiCreateTeacherSubjectInput $input) => new CreateTeacherSubjectInput(
						$input->getTeacherId(),
						$input->getSubjectId(),
					),
					$createTeacherSubjectsRequest->getTeacherSubjects(),
				),
			);
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function listTeacherSubjects(int &$responseCode, array &$responseHeaders): array|object|null
	{
		return $this->exceptionHandler->executeWithHandle(function ()
		{
			$teacherSubjects = $this->subjectApi->listAllTeacherSubjects();
			return new ApiTeacherSubjectsList([
				'teacherSubjects' => TeacherSubjectModelConverter::convertTeacherSubjectsToApiTeacherSubjects($teacherSubjects),
			]);
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function deleteTeacherSubjects(DeleteTeacherSubjectsRequest $deleteTeacherSubjectsRequest, int &$responseCode, array &$responseHeaders): void
	{
		$this->exceptionHandler->executeWithHandle(function () use ($deleteTeacherSubjectsRequest)
		{
			$this->subjectApi->deleteTeacherSubjects($deleteTeacherSubjectsRequest->getTeacherSubjectIds());
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function createCourses(ApiCreateCoursesRequest $createCoursesRequest, int &$responseCode, array &$responseHeaders): void
	{
		$this->exceptionHandler->executeWithHandle(function () use ($createCoursesRequest)
		{
			$this->subjectApi->createCourses(
				array_map(
					static fn(ApiCreateCourseInput $input) => new CreateCourseInput(
						$input->getGroupId(),
						$input->getTeacherSubjectId(),
					),
					$createCoursesRequest->getCourses(),
				),
			);
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function deleteCourses(DeleteCoursesRequest $deleteCoursesRequest, int &$responseCode, array &$responseHeaders): void
	{
		$this->exceptionHandler->executeWithHandle(function () use ($deleteCoursesRequest)
		{
			$this->subjectApi->deleteCourses($deleteCoursesRequest->getCourseIds());
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function listCourses(int &$responseCode, array &$responseHeaders): array|object|null
	{
		return $this->exceptionHandler->executeWithHandle(function ()
		{
			$courses = $this->subjectApi->listAllCourses();

			return new ApiCoursesList([
				'courses' => CourseModelConverter::convertCoursesToApiCourses($courses),
			]);
		}, $responseCode, $responseHeaders);
	}
}