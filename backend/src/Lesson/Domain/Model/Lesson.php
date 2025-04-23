<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Model;

use App\Common\Uuid\UuidInterface;
use App\Lesson\Domain\Exception\DomainException;

class Lesson
{
	private const int HOURS_IN_DAY = 24;
	private const int MINUTES_IN_HOUR = 60;
	private const int SECONDS_IN_MINUTE = 60;
	private const int SECONDS_IN_DAY = self::HOURS_IN_DAY * self::MINUTES_IN_HOUR * self::SECONDS_IN_MINUTE;

	/**
	 * @throws DomainException
	 */
	public function __construct(
		private readonly UuidInterface $lessonId,
		private \DateTimeInterface     $date,
		private int                    $startTime,
		private int                    $duration,
		private UuidInterface          $courseId,
		private ?UuidInterface         $locationId,
		private ?string                $description,
	)
	{
		self::assertStartTimeIsValid($this->startTime);
		self::assertDurationIsValid($this->startTime, $this->duration);
	}

	public function getLessonId(): UuidInterface
	{
		return $this->lessonId;
	}

	public function getDate(): \DateTimeInterface
	{
		return $this->date;
	}

	public function setDate(\DateTimeInterface $date): void
	{
		$this->date = $date;
	}

	public function getStartTime(): int
	{
		return $this->startTime;
	}

	/**
	 * @throws DomainException
	 */
	public function setStartTime(int $startTime): void
	{
		self::assertStartTimeIsValid($startTime);
		self::assertDurationIsValid($startTime, $this->duration);
		$this->startTime = $startTime;
	}

	public function getDuration(): int
	{
		return $this->duration;
	}

	/**
	 * @throws DomainException
	 */
	public function setDuration(int $duration): void
	{
		self::assertDurationIsValid($this->startTime, $duration);
		$this->duration = $duration;
	}

	public function getCourseId(): UuidInterface
	{
		return $this->courseId;
	}

	public function setCourseId(UuidInterface $courseId): void
	{
		$this->courseId = $courseId;
	}

	public function getLocationId(): ?UuidInterface
	{
		return $this->locationId;
	}

	public function setLocationId(?UuidInterface $locationId): void
	{
		$this->locationId = $locationId;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function setDescription(?string $description): void
	{
		$this->description = $description;
	}

	/**
	 * @throws DomainException
	 */
	private static function assertStartTimeIsValid(int $startTime): void
	{
		if ($startTime < 0)
		{
			throw new DomainException('Start time must be positive integer', DomainException::INVALID_LESSON_START_TIME);
		}
	}

	/**
	 * @throws DomainException
	 */
	private static function assertDurationIsValid(int $startTime, int $duration): void
	{
		if ($duration < 0)
		{
			throw new DomainException('Duration must be positive integer', DomainException::INVALID_LESSON_DURATION);
		}

		if ($startTime + $duration > self::SECONDS_IN_DAY)
		{
			throw new DomainException('Start time must be less than or equal to day', DomainException::INVALID_LESSON_DURATION);
		}
	}
}