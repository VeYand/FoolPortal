<?php
declare(strict_types=1);

namespace App\Subject\Infrastructure\Repository;

use App\Subject\Domain\Model\Course;
use App\Subject\Domain\Repository\CourseRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class CourseRepository implements CourseRepositoryInterface
{
	private EntityRepository $repository;

	public function __construct(
		private readonly EntityManagerInterface $entityManager,
	)
	{
		$this->repository = $this->entityManager->getRepository(Course::class);
	}

	public function find(string $courseId): ?Course
	{
		return $this->repository->find($courseId);
	}

	/**
	 * @inheritDoc
	 */
	public function findAll(): array
	{
		return $this->repository->findAll();
	}

	public function findByTeacherSubjectAndGroup(string $teacherSubjectId, string $groupId): ?Course
	{
		return $this->repository->findOneBy([
			'teacherSubjectId' => $teacherSubjectId,
			'groupId' => $groupId,
		]);
	}

	/**
	 * @inheritDoc
	 */
	public function findByTeacherSubjects(array $teacherSubjectIds): array
	{
		return $this->repository->findBy([
			'teacherSubjectId' => $teacherSubjectIds,
		]);
	}

	public function store(Course $course): string
	{
		$this->entityManager->persist($course);
		$this->entityManager->flush();
		return $course->getCourseId();
	}

	/**
	 * @inheritDoc
	 */
	public function delete(array $courses): void
	{
		foreach ($courses as $course)
		{
			$this->entityManager->remove($course);
		}
		$this->entityManager->flush();
	}
}