<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Model;

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
		private readonly string    $lessonId,
		private \DateTimeInterface $date,
		private int                $startTime,
		private int                $duration,
		private string             $courseId,
		private ?string            $locationId,
		private ?string            $description,
	)
	{
		self::assertStartTimeIsValid($this->startTime);
		self::assertDurationIsValid($this->startTime, $this->duration);
	}

	public function getLessonId(): string
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
		self::assertStartTimeIsValid($this->startTime);
		self::assertDurationIsValid($this->startTime, $this->duration);
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
		self::assertDurationIsValid($this->startTime, $this->duration);
		$this->duration = $duration;
	}

	public function getCourseId(): string
	{
		return $this->courseId;
	}

	public function setCourseId(string $courseId): void
	{
		$this->courseId = $courseId;
	}

	public function getLocationId(): ?string
	{
		return $this->locationId;
	}

	public function setLocationId(?string $locationId): void
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
	public static function assertStartTimeIsValid(int $startTime): void
	{
		if ($startTime < 0)
		{
			throw new DomainException('Start time must be positive integer', DomainException::INVALID_LESSON_START_TIME);
		}
	}

	/**
	 * @throws DomainException
	 */
	public static function assertDurationIsValid(int $startTime, int $duration): void
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