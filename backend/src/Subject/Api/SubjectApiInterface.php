<?php
declare(strict_types=1);

namespace App\Subject\Api;

use App\Subject\Api\Exception\ApiException;
use App\Subject\App\Query\Data\CourseData;
use App\Subject\App\Query\Data\SubjectData;
use App\Subject\App\Query\Data\TeacherSubjectData;

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
	 * @throws ApiException
	 */
	public function createTeacherSubject(string $teacherId, string $subjectId): void;

	/**
	 * @throws ApiException
	 */
	public function deleteTeacherSubject(string $teacherSubjectId): void;

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
	 * @return CourseData[]
	 */
	public function listAllCourses(): array;
}