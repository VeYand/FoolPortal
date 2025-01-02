<?php
declare(strict_types=1);

namespace App\Subject\Api;

use App\Common\Uuid\UuidInterface;
use App\Subject\Api\Exception\ApiException;
use App\Subject\App\Query\Data\CourseData;
use App\Subject\App\Query\Data\SubjectData;
use App\Subject\App\Query\Data\TeacherSubjectData;
use App\Subject\App\Query\Spec\ListCoursesSpec;
use App\Subject\App\Query\Spec\ListTeacherSubjectsSpec;
use App\Subject\App\Service\Input\CreateCourseInput;
use App\Subject\App\Service\Input\CreateTeacherSubjectInput;

interface SubjectApiInterface
{
	/**
	 * @throws ApiException
	 */
	public function createSubject(string $subjectName): void;

	/**
	 * @throws ApiException
	 */
	public function updateSubject(UuidInterface $subjectId, string $subjectName): void;

	/**
	 * @throws ApiException
	 */
	public function deleteSubject(UuidInterface $subjectId): void;

	/**
	 * @param CreateTeacherSubjectInput[] $inputs
	 * @throws ApiException
	 */
	public function createTeacherSubjects(array $inputs): void;

	/**
	 * @param UuidInterface[] $teacherSubjectIds
	 * @throws ApiException
	 */
	public function deleteTeacherSubjects(array $teacherSubjectIds): void;

	/**
	 * @param CreateCourseInput[] $inputs
	 * @throws ApiException
	 */
	public function createCourses(array $inputs): void;

	/**
	 * @param UuidInterface[] $courseIds
	 * @throws ApiException
	 */
	public function deleteCourses(array $courseIds): void;

	/**
	 * @return SubjectData[]
	 */
	public function listAllSubjects(): array;

	/**
	 * @return TeacherSubjectData[]
	 */
	public function listTeacherSubjects(ListTeacherSubjectsSpec $spec): array;

	/**
	 * @return CourseData[]
	 */
	public function listCourses(ListCoursesSpec $spec): array;

	public function isCourseExists(UuidInterface $courseId): bool;
}