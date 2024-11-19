<?php
declare(strict_types=1);

namespace App\Tests\Unit\Lesson\Domain\Service;

use App\Common\Uuid\UuidProvider;
use App\Lesson\Domain\Exception\DomainException;
use App\Lesson\Domain\Model\Lesson;
use App\Lesson\Domain\Repository\AttachmentRepositoryInterface;
use App\Lesson\Domain\Repository\LessonAttachmentRepositoryInterface;
use App\Lesson\Domain\Repository\LessonRepositoryInterface;
use App\Lesson\Domain\Repository\LocationRepositoryInterface;
use App\Lesson\Domain\Service\Input\CreateLessonInput;
use App\Lesson\Domain\Service\Input\UpdateLessonInput;
use App\Lesson\Domain\Service\LessonService;
use App\Tests\Unit\Lesson\Infrastructure\AttachmentInMemoryRepository;
use App\Tests\Unit\Lesson\Infrastructure\LessonAttachmentInMemoryRepository;
use App\Tests\Unit\Lesson\Infrastructure\LessonInMemoryRepository;
use App\Tests\Unit\Lesson\Infrastructure\LocationInMemoryRepository;
use PHPUnit\Framework\TestCase;

class LessonServiceTest extends TestCase
{
	private AttachmentRepositoryInterface $attachmentRepository;
	private LessonAttachmentRepositoryInterface $lessonAttachmentRepository;
	private LessonRepositoryInterface $lessonRepository;
	private LocationRepositoryInterface $locationRepository;
	private LessonService $lessonService;

	protected function setUp(): void
	{
		$this->attachmentRepository = new AttachmentInMemoryRepository();
		$this->lessonAttachmentRepository = new LessonAttachmentInMemoryRepository();
		$this->lessonRepository = new LessonInMemoryRepository();
		$this->locationRepository = new LocationInMemoryRepository();
		$this->lessonService = new LessonService(
			$this->lessonRepository,
			$this->lessonAttachmentRepository,
			$this->locationRepository,
			new UuidProvider(),
		);
	}

	public function testCreateLessonSuccess(): void
	{
		$input = new CreateLessonInput(
			new \DateTime('tomorrow'),
			10000,
			90,
			'course-123',
			null,
			'Test lesson',
		);

		$lessonId = $this->lessonService->create($input);

		$lesson = $this->lessonRepository->find($lessonId);
		$this->assertNotNull($lesson);
		$this->assertEquals('Test lesson', $lesson->getDescription());
		// еше проверки
	}

	public function testCreateLessonDatePassed(): void
	{
		$input = new CreateLessonInput(
			new \DateTime('yesterday'),
			10000,
			90,
			'course-123',
			null,
			'Test lesson',
		);

		$this->expectException(DomainException::class);
		$this->expectExceptionCode(DomainException::LESSON_DATE_ALREADY_PASSED);

		$this->lessonService->create($input);
	}

	public function testUpdateLessonSuccess(): void
	{
		$existingLesson = new Lesson(
			'existing-uuid', // в константу
			new \DateTime('tomorrow'),
			1000,
			60,
			'course-123',
			null,
			'Old description',
		);
		$this->lessonRepository->store($existingLesson);

		$input = new UpdateLessonInput(
			'existing-uuid',
			null,
			123, // const
			null,
			null,
			null,
			'Updated description', //  const
		);
		$this->lessonService->update($input);

		$updatedLesson = $this->lessonRepository->find('existing-uuid');
		$this->assertSame(123, $updatedLesson->getStartTime());
		$this->assertSame('Updated description', $updatedLesson->getDescription());
	}

	public function testUpdateLessonNotFound(): void
	{
		$input = new UpdateLessonInput(
			'non-existent-id',
			null,
			null,
			null,
			null,
			null,
			null,
		);

		$this->expectException(DomainException::class);
		$this->expectExceptionCode(DomainException::LESSON_NOT_FOUND);

		$this->lessonService->update($input);
	}

	public function testDeleteLessons(): void
	{
		$existingLesson1 = new Lesson('lesson-1', new \DateTime('today'), 9000, 60, 'course-123', null, 'Lesson 1');
		$existingLesson2 = new Lesson('lesson-2', new \DateTime('today'), 10000, 60, 'course-123', null, 'Lesson 2');
		$this->lessonRepository->store($existingLesson1);
		$this->lessonRepository->store($existingLesson2);

		$this->lessonService->delete(['lesson-1', 'lesson-2']);

		$this->assertCount(0, $this->lessonRepository->findByIds(['lesson-1', 'lesson-2'])); // id in const
	}
}
