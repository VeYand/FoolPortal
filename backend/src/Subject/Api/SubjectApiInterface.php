<?php
declare(strict_types=1);

namespace App\Subject\Api;

use App\Subject\Api\Exception\ApiException;
use App\Subject\App\Query\Data\CourseData;
use App\Subject\App\Query\Data\SubjectData;
use App\Subject\App\Query\Data\TeacherSubjectData;
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
	public function updateSubject(string $subjectId, string $subjectName): void;

	/**
	 * @throws ApiException
	 */
	public function deleteSubject(string $subjectId): void;

	/**
	 * @param CreateTeacherSubjectInput[] $inputs
	 * @throws ApiException
	 */
	public function createTeacherSubjects(array $inputs): void;

	/**
	 * @param string[] $teacherSubjectIds
	 * @throws ApiException
	 */
	public function deleteTeacherSubjects(array $teacherSubjectIds): void;

	/**
	 * @throws ApiException
	 */
	public function createCourse(string $teacherSubjectId, string $groupId): void;

	/**
	 * @throws ApiException
	 */
	public function deleteCourse(string $courseId): void;

	/**
	 * @return SubjectData[]
	 */
	public function listAllSubjects(): array;

	/**
	 * @return TeacherSubjectData[]
	 */
	public function listAllTeacherSubjects(): array;

	/**
	 * @return TeacherSubjectData[]
	 */
	public function listTeacherSubjectsByGroup(string $groupId): array;

	/**
	 * @return CourseData[]
	 */
	public function listAllCourses(): array;

	public function isCourseExists(string $courseId): bool;
}