<?php
declare(strict_types=1);

namespace App\Controller;

use App\Common\Uuid\UuidProviderInterface;
use App\Controller\Converter\CourseModelConverter;
use App\Controller\Converter\SubjectModelConverter;
use App\Controller\Converter\TeacherSubjectModelConverter;
use App\Controller\Exception\ExceptionHandler;
use App\Subject\Api\SubjectApiInterface;
use App\Subject\App\Query\Spec\ListCoursesSpec;
use App\Subject\App\Query\Spec\ListTeacherSubjectsSpec;
use OpenAPI\Server\Api\SubjectApiInterface as SubjectApiHandlerInterface;
use OpenAPI\Server\Model\CreateCourseInput as ApiCreateCourseInput;
use App\Subject\App\Service\Input\CreateCourseInput;
use OpenAPI\Server\Model\CreateCoursesRequest as ApiCreateCoursesRequest;
use OpenAPI\Server\Model\EmptyResponse as ApiEmptyResponse;
use OpenAPI\Server\Model\CreateSubjectRequest;
use App\Subject\App\Service\Input\CreateTeacherSubjectInput;
use OpenAPI\Server\Model\CreateTeacherSubjectInput as ApiCreateTeacherSubjectInput;
use OpenAPI\Server\Model\CreateTeacherSubjectsRequest;
use OpenAPI\Server\Model\DeleteCoursesRequest;
use OpenAPI\Server\Model\DeleteSubjectRequest;
use OpenAPI\Server\Model\DeleteTeacherSubjectsRequest;
use OpenAPI\Server\Model\ListCoursesRequest;
use OpenAPI\Server\Model\ListTeacherSubjectsRequest as ApiListTeacherSubjectsRequest;
use OpenAPI\Server\Model\SubjectsList as ApiSubjectsList;
use OpenAPI\Server\Model\CoursesList as ApiCoursesList;
use OpenAPI\Server\Model\TeacherSubjectsList as ApiTeacherSubjectsList;
use OpenAPI\Server\Model\UpdateSubjectRequest;

readonly class SubjectApiHandler implements SubjectApiHandlerInterface
{
	public function __construct(
		private SubjectApiInterface   $subjectApi,
		private ExceptionHandler      $exceptionHandler,
		private UuidProviderInterface $uuidProvider,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function createSubject(CreateSubjectRequest $createSubjectRequest, int &$responseCode, array &$responseHeaders): array|null|object
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($createSubjectRequest)
		{
			$this->subjectApi->createSubject($createSubjectRequest->getName());
			return new ApiEmptyResponse();
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
	public function updateSubject(UpdateSubjectRequest $updateSubjectRequest, int &$responseCode, array &$responseHeaders): array|null|object
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($updateSubjectRequest)
		{
			$this->subjectApi->updateSubject(
				$this->uuidProvider->fromStringToUuid($updateSubjectRequest->getSubjectId()),
				$updateSubjectRequest->getName(),
			);
			return new ApiEmptyResponse();
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function deleteSubject(DeleteSubjectRequest $deleteSubjectRequest, int &$responseCode, array &$responseHeaders): array|null|object
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($deleteSubjectRequest)
		{
			$this->subjectApi->deleteSubject(
				$this->uuidProvider->fromStringToUuid($deleteSubjectRequest->getSubjectId()),
			);
			return new ApiEmptyResponse();
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function createTeacherSubjects(CreateTeacherSubjectsRequest $createTeacherSubjectsRequest, int &$responseCode, array &$responseHeaders): array|null|object
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($createTeacherSubjectsRequest)
		{
			$this->subjectApi->createTeacherSubjects(
				array_map(
					fn(ApiCreateTeacherSubjectInput $input) => new CreateTeacherSubjectInput(
						$this->uuidProvider->fromStringToUuid($input->getTeacherId()),
						$this->uuidProvider->fromStringToUuid($input->getSubjectId()),
					),
					$createTeacherSubjectsRequest->getTeacherSubjects(),
				),
			);
			return new ApiEmptyResponse();
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function listTeacherSubjects(ApiListTeacherSubjectsRequest $listTeacherSubjectsRequest, int &$responseCode, array &$responseHeaders): array|object|null
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($listTeacherSubjectsRequest)
		{
			$teacherSubjects = $this->subjectApi->listTeacherSubjects(
				new ListTeacherSubjectsSpec(
					$this->uuidProvider->fromStringsToUuids($listTeacherSubjectsRequest->getCourseIds()),
				),
			);
			return new ApiTeacherSubjectsList([
				'teacherSubjects' => TeacherSubjectModelConverter::convertTeacherSubjectsToApiTeacherSubjects($teacherSubjects),
			]);
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function deleteTeacherSubjects(DeleteTeacherSubjectsRequest $deleteTeacherSubjectsRequest, int &$responseCode, array &$responseHeaders): array|null|object
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($deleteTeacherSubjectsRequest)
		{
			$this->subjectApi->deleteTeacherSubjects(
				$this->uuidProvider->fromStringsToUuids($deleteTeacherSubjectsRequest->getTeacherSubjectIds()),
			);
			return new ApiEmptyResponse();
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function createCourses(ApiCreateCoursesRequest $createCoursesRequest, int &$responseCode, array &$responseHeaders): array|null|object
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($createCoursesRequest)
		{
			$this->subjectApi->createCourses(
				array_map(
					fn(ApiCreateCourseInput $input) => new CreateCourseInput(
						$this->uuidProvider->fromStringToUuid($input->getGroupId()),
						$this->uuidProvider->fromStringToUuid($input->getTeacherSubjectId()),
					),
					$createCoursesRequest->getCourses(),
				),
			);
			return new ApiEmptyResponse();
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function deleteCourses(DeleteCoursesRequest $deleteCoursesRequest, int &$responseCode, array &$responseHeaders): array|null|object
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($deleteCoursesRequest)
		{
			$this->subjectApi->deleteCourses(
				$this->uuidProvider->fromStringsToUuids($deleteCoursesRequest->getCourseIds()),
			);
			return new ApiEmptyResponse();
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function listCourses(ListCoursesRequest $listCoursesRequest, int &$responseCode, array &$responseHeaders): array|object|null
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($listCoursesRequest)
		{
			$courses = $this->subjectApi->listCourses(
				new ListCoursesSpec(
					$this->uuidProvider->fromStringsToUuids($listCoursesRequest->getCourseIds()),
				),
			);

			return new ApiCoursesList([
				'courses' => CourseModelConverter::convertCoursesToApiCourses($courses),
			]);
		}, $responseCode, $responseHeaders);
	}
}