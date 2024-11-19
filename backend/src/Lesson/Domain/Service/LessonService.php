<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Service;

use App\Common\Uuid\UuidProviderInterface;
use App\Lesson\Domain\Exception\DomainException;
use App\Lesson\Domain\Model\Lesson;
use App\Lesson\Domain\Repository\LessonAttachmentRepositoryInterface;
use App\Lesson\Domain\Repository\LessonRepositoryInterface;
use App\Lesson\Domain\Repository\LocationReadRepositoryInterface;
use App\Lesson\Domain\Service\Input\CreateLessonInput;
use App\Lesson\Domain\Service\Input\UpdateLessonInput;

readonly class LessonService
{
	public function __construct(
		private LessonRepositoryInterface           $lessonRepository,
		private LessonAttachmentRepositoryInterface $lessonAttachmentRepository,
		private LocationReadRepositoryInterface     $locationReadRepository,
		private UuidProviderInterface               $uuidProvider,
	)
	{
	}

	/**
	 * @throws DomainException
	 */
	public function create(CreateLessonInput $input): string
	{
		if (!is_null($input->locationId))
		{
			$this->assertLocationExists($input->locationId);
		}

		self::assertDateNotPassed($input->date);

		$lesson = new Lesson(
			$this->uuidProvider->generate(),
			$input->date,
			$input->startTime,
			$input->duration,
			$input->courseId,
			$input->locationId,
			$input->description,
		);

		return $this->lessonRepository->store($lesson);
	}

	/**
	 * @throws DomainException
	 */
	public function update(UpdateLessonInput $input): void
	{
		$lesson = $this->lessonRepository->find($input->lessonId);

		if (is_null($lesson))
		{
			throw new DomainException('Lesson not found', DomainException::LESSON_NOT_FOUND);
		}

		if (!is_null($input->date))
		{
			self::assertDateNotPassed($input->date);
			$lesson->setDate($input->date);
		}

		if (!is_null($input->startTime))
		{
			$lesson->setStartTime($input->startTime);
		}

		if (!is_null($input->duration))
		{
			$lesson->setDuration($input->duration);
		}

		if (!is_null($input->courseId))
		{
			$lesson->setCourseId($input->courseId);
		}

		if (!is_null($input->locationId))
		{
			$this->assertLocationExists($input->locationId);
			$lesson->setLocationId($input->locationId);
		}

		if (!is_null($input->description))
		{
			$lesson->setDescription($input->description);
		}

		$this->lessonRepository->storeList([$lesson]);
	}

	/**
	 * @param string[] $lessonIds
	 *
	 * TODO Уязвимость: не удаляется вложение, если его перестают использовать
	 * Решение - создать библиотеку вложений, либо реализовать удаление вложения, если его перестают использовать
	 */
	public function delete(array $lessonIds): void
	{
		$lessons = $this->lessonRepository->findByIds($lessonIds);

		if (!empty($lessons))
		{
			$lessonAttachments = $this->lessonAttachmentRepository->findByLessons($lessonIds);
			$this->lessonAttachmentRepository->delete($lessonAttachments);
			$this->lessonRepository->delete($lessons); // TODO Исправить ошибку типизации
		}
	}

	/**
	 * @throws DomainException
	 */
	public function assertLocationExists(string $locationId): void
	{
		$location = $this->locationReadRepository->find($locationId);

		if (is_null($location))
		{
			throw new DomainException('Location not found', DomainException::LOCATION_NOT_FOUND);
		}
	}

	/**
	 * @throws DomainException
	 */
	private static function assertDateNotPassed(\DateTimeInterface $date): void
	{
		$today = new \DateTime('today');

		if ($today > $date)
		{
			throw new DomainException('Date is already passed', DomainException::LESSON_DATE_ALREADY_PASSED);
		}
	}
}