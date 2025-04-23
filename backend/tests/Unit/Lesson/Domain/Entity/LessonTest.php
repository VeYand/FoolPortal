<?php
declare(strict_types=1);

namespace App\Tests\Unit\Lesson\Domain\Entity;

use App\Common\Uuid\UuidProvider;
use App\Lesson\Domain\Exception\DomainException;
use App\Lesson\Domain\Model\Lesson;
use PHPUnit\Framework\TestCase;

class LessonTest extends TestCase
{
	private const int HOURS_IN_DAY = 24;
	private const int MINUTES_IN_HOUR = 60;
	private const int SECONDS_IN_MINUTE = 60;
	private const int SECONDS_IN_DAY = self::HOURS_IN_DAY * self::MINUTES_IN_HOUR * self::SECONDS_IN_MINUTE;

	/**
	 * @throws DomainException
	 */
	public function testValidCreationAndSetters(): void
	{
		$uuidProvider = new UuidProvider();
		$lessonId = $uuidProvider->generate();
		$courseId = $uuidProvider->generate();
		$locationId = $uuidProvider->generate();
		$date = new \DateTimeImmutable('2025-04-23');

		$lesson = new Lesson($lessonId, $date, 3600, 1800, $courseId, $locationId, 'desc');
		$this->assertEquals($date, $lesson->getDate());
		$this->assertEquals(3600, $lesson->getStartTime());
		$this->assertEquals(1800, $lesson->getDuration());
		$this->assertEquals($courseId->toString(), $lesson->getCourseId()->toString());
		$this->assertEquals($locationId->toString(), $lesson->getLocationId()->toString());
		$this->assertEquals('desc', $lesson->getDescription());

		$newDate = new \DateTimeImmutable('2025-05-01');
		$lesson->setDate($newDate);
		$lesson->setCourseId($courseId);
		$lesson->setLocationId(null);
		$lesson->setDescription(null);

		$this->assertEquals($newDate, $lesson->getDate());
		$this->assertNull($lesson->getLocationId());
		$this->assertNull($lesson->getDescription());
	}

	/**
	 * @throws DomainException
	 */
	public function testAssertPositiveStartTimeIsValid(): void
	{
		$uuid = (new UuidProvider())->generate();
		$date = new \DateTimeImmutable('2025-04-23');

		new Lesson($uuid, $date, 1, 1800, $uuid, $uuid, 'desc');

		$this->assertTrue(true);
	}

	/**
	 * @throws DomainException
	 */
	public function testAssertZeroStartTimeIsValid(): void
	{
		$uuid = (new UuidProvider())->generate();
		$date = new \DateTimeImmutable('2025-04-23');

		new Lesson($uuid, $date, 0, 1800, $uuid, $uuid, 'desc');

		$this->assertTrue(true);
	}

	public function testAssertNegativeStartTimeIsInvalid(): void
	{
		$uuid = (new UuidProvider())->generate();
		$date = new \DateTimeImmutable('2025-04-23');

		$this->expectException(DomainException::class);
		$this->expectExceptionCode(DomainException::INVALID_LESSON_START_TIME);

		new Lesson($uuid, $date, -1, 1800, $uuid, $uuid, 'desc');
	}


	/**
	 * @throws DomainException
	 */
	public function testAssertPositiveDurationIsValid(): void
	{
		$uuid = (new UuidProvider())->generate();
		$date = new \DateTimeImmutable('2025-04-23');

		new Lesson($uuid, $date, 1000, 1, $uuid, $uuid, 'desc');

		$this->assertTrue(true);
	}

	/**
	 * @throws DomainException
	 */
	public function testAssertZeroDurationIsValid(): void
	{
		$uuid = (new UuidProvider())->generate();
		$date = new \DateTimeImmutable('2025-04-23');

		new Lesson($uuid, $date, 1000, 0, $uuid, $uuid, 'desc');

		$this->assertTrue(true);
	}

	public function testAssertNegativeDurationIsInvalid(): void
	{
		$uuid = (new UuidProvider())->generate();
		$date = new \DateTimeImmutable('2025-04-23');

		$this->expectException(DomainException::class);
		$this->expectExceptionCode(DomainException::INVALID_LESSON_DURATION);

		new Lesson($uuid, $date, 0, -1, $uuid, $uuid, 'desc');
	}

	public function testStartTimePlusDurationExceedsDayLimit(): void
	{
		$uuid = (new UuidProvider())->generate();
		$date = new \DateTimeImmutable();

		$this->expectException(DomainException::class);
		$this->expectExceptionCode(DomainException::INVALID_LESSON_DURATION);

		new Lesson(
			$uuid,
			$date,
			self::SECONDS_IN_DAY,
			1,
			$uuid,
			$uuid,
			'desc',
		);
	}

	public function testStartTimePlusDurationEqualsDayLimit(): void
	{
		$uuid = (new UuidProvider())->generate();
		$date = new \DateTimeImmutable();

		try
		{
			new Lesson(
				$uuid,
				$date,
				self::SECONDS_IN_DAY - 100,
				100,
				$uuid,
				$uuid,
				'desc',
			);
			$this->assertTrue(true);
		}
		catch (DomainException)
		{
			$this->fail('Valid time range was rejected');
		}
	}

	/**
	 * @throws DomainException
	 */
	public function testSetStartTimeWithValidValue(): void
	{
		$lesson = $this->createValidLesson();
		$lesson->setStartTime(100);
		$this->assertEquals(100, $lesson->getStartTime());
	}

	/**
	 * @throws DomainException
	 */
	public function testSetStartTimeWithNegativeValueThrowsException(): void
	{
		$lesson = $this->createValidLesson();

		$this->expectException(DomainException::class);
		$this->expectExceptionCode(DomainException::INVALID_LESSON_START_TIME);

		$lesson->setStartTime(-1);
	}

	/**
	 * @throws DomainException
	 */
	public function testSetStartTimeExceedingDayLimitWithCurrentDuration(): void
	{
		$lesson = $this->createValidLesson();
		$lesson->setDuration(self::SECONDS_IN_DAY);

		$this->expectException(DomainException::class);
		$lesson->setStartTime(1);
	}

	/**
	 * @throws DomainException
	 */
	public function testSetDurationWithValidValue(): void
	{
		$lesson = $this->createValidLesson();
		$lesson->setDuration(500);
		$this->assertEquals(500, $lesson->getDuration());
	}

	/**
	 * @throws DomainException
	 */
	public function testSetDurationWithNegativeValueThrowsException(): void
	{
		$lesson = $this->createValidLesson();

		$this->expectException(DomainException::class);
		$this->expectExceptionCode(DomainException::INVALID_LESSON_DURATION);

		$lesson->setDuration(-1);
	}

	/**
	 * @throws DomainException
	 */
	public function testSetDurationExceedingDayLimitWithCurrentStartTime(): void
	{
		$lesson = $this->createValidLesson();
		$lesson->setStartTime(self::SECONDS_IN_DAY - 100);

		$this->expectException(DomainException::class);
		$lesson->setDuration(101);
	}

	/**
	 * @throws DomainException
	 */
	private function createValidLesson(): Lesson
	{
		$uuid = (new UuidProvider())->generate();
		return new Lesson(
			$uuid,
			new \DateTimeImmutable(),
			0,
			100,
			$uuid,
			$uuid,
			'desc',
		);
	}
}