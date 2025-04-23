<?php
declare(strict_types=1);

namespace App\Tests\Unit\Lesson\Domain\Service;

use App\Common\Uuid\UuidProvider;
use App\Lesson\Domain\Exception\DomainException;
use App\Lesson\Domain\Model\Attachment;
use App\Lesson\Domain\Model\Lesson;
use App\Lesson\Domain\Model\LessonAttachment;
use App\Lesson\Domain\Service\LessonAttachmentService;
use App\Tests\Unit\Lesson\Infrastructure\InMemoryAttachmentRepository;
use App\Tests\Unit\Lesson\Infrastructure\InMemoryLessonAttachmentRepository;
use App\Tests\Unit\Lesson\Infrastructure\InMemoryLessonRepository;
use PHPUnit\Framework\TestCase;

class LessonAttachmentServiceTest extends TestCase
{
	private LessonAttachmentService $service;
	private InMemoryLessonAttachmentRepository $lessonAttachmentRepo;
	private InMemoryAttachmentRepository $attachmentRepository;
	private InMemoryLessonRepository $lessonRepository;
	private UuidProvider $uuidProvider;

	protected function setUp(): void
	{
		$this->lessonAttachmentRepo = new InMemoryLessonAttachmentRepository();
		$this->attachmentRepository = new InMemoryAttachmentRepository();
		$this->lessonRepository = new InMemoryLessonRepository();
		$this->uuidProvider = new UuidProvider();
		$this->service = new LessonAttachmentService(
			$this->lessonAttachmentRepo,
			$this->attachmentRepository,
			$this->lessonRepository,
			$this->uuidProvider,
		);
	}

	/**
	 * @throws DomainException
	 */
	public function testAddAttachmentToLessonSuccess(): void
	{
		$lessonId = $this->uuidProvider->generate();
		$attachmentId = $this->uuidProvider->generate();
		$lesson = new Lesson($lessonId, new \DateTimeImmutable(), 0, 0, $this->uuidProvider->generate(), null, null);
		$this->lessonRepository->store($lesson);
		$attachment = new Attachment($attachmentId, 'file', null, '/tmp/file', 'txt');
		$this->attachmentRepository->store($attachment);

		$storedId = $this->service->addAttachmentToLesson($lessonId, $attachmentId);

		$this->assertEquals($storedId->toString(), $this->lessonAttachmentRepo->findByLessonAndAttachment($lessonId, $attachmentId)->getLessonAttachmentId()->toString());
	}

	/**
	 * @throws DomainException
	 */
	public function testAddAttachmentToLessonThrowsIfAlreadyExists(): void
	{
		$lessonId = $this->uuidProvider->generate();
		$attachmentId = $this->uuidProvider->generate();
		$lesson = new Lesson($lessonId, new \DateTimeImmutable(), 0, 0, $this->uuidProvider->generate(), null, null);
		$this->lessonRepository->store($lesson);
		$attachment = new Attachment($attachmentId, 'file', null, '/tmp/file', 'txt');
		$this->attachmentRepository->store($attachment);
		$existing = new LessonAttachment($this->uuidProvider->generate(), $lessonId, $attachmentId);
		$this->lessonAttachmentRepo->store($existing);

		$this->expectException(DomainException::class);
		$this->expectExceptionCode(DomainException::LESSON_ATTACHMENT_ALREADY_EXISTS);

		$this->service->addAttachmentToLesson($lessonId, $attachmentId);
	}

	public function testAddAttachmentThrowsIfLessonNotFound(): void
	{
		$lessonId = $this->uuidProvider->generate();
		$attachmentId = $this->uuidProvider->generate();
		$attachment = new Attachment($attachmentId, 'file', null, '/tmp/file', 'txt');
		$this->attachmentRepository->store($attachment);

		$this->expectException(DomainException::class);
		$this->expectExceptionCode(DomainException::LESSON_NOT_FOUND);

		$this->service->addAttachmentToLesson($lessonId, $attachmentId);
	}

	/**
	 * @throws DomainException
	 */
	public function testAddAttachmentThrowsIfAttachmentNotFound(): void
	{
		$lessonId = $this->uuidProvider->generate();
		$attachmentId = $this->uuidProvider->generate();
		$lesson = new Lesson($lessonId, new \DateTimeImmutable(), 0, 0, $this->uuidProvider->generate(), null, null);
		$this->lessonRepository->store($lesson);

		$this->expectException(DomainException::class);
		$this->expectExceptionCode(DomainException::ATTACHMENT_NOT_FOUND);

		$this->service->addAttachmentToLesson($lessonId, $attachmentId);
	}

	public function testRemoveAttachmentFromLessonRemovesExisting(): void
	{
		$lessonId = $this->uuidProvider->generate();
		$attachmentId = $this->uuidProvider->generate();
		$assoc = new LessonAttachment($this->uuidProvider->generate(), $lessonId, $attachmentId);
		$this->lessonAttachmentRepo->store($assoc);

		$this->service->removeAttachmentFromLesson($lessonId, $attachmentId);

		$this->assertNull($this->lessonAttachmentRepo->findByLessonAndAttachment($lessonId, $attachmentId));
	}

	public function testRemoveAttachmentFromLessonNoOpWhenNotExists(): void
	{
		$this->service->removeAttachmentFromLesson($this->uuidProvider->generate(), $this->uuidProvider->generate());
		$this->assertTrue(true);
	}

	/**
	 * @throws DomainException
	 */
	public function testAddMultipleAttachmentsToSameLesson(): void
	{
		$lessonId = $this->uuidProvider->generate();
		$lesson = new Lesson($lessonId, new \DateTimeImmutable(), 0, 0, $this->uuidProvider->generate(), null, null);
		$this->lessonRepository->store($lesson);
		$att1 = new Attachment($this->uuidProvider->generate(), 'file1', null, '/tmp/file1', 'txt');
		$att2 = new Attachment($this->uuidProvider->generate(), 'file2', null, '/tmp/file2', 'jpg');
		$this->attachmentRepository->store($att1);
		$this->attachmentRepository->store($att2);

		$id1 = $this->service->addAttachmentToLesson($lessonId, $att1->getAttachmentId());
		$id2 = $this->service->addAttachmentToLesson($lessonId, $att2->getAttachmentId());

		$this->assertNotEquals($id1->toString(), $id2->toString());
		$this->assertNotNull($this->lessonAttachmentRepo->findByLessonAndAttachment($lessonId, $att1->getAttachmentId()));
		$this->assertNotNull($this->lessonAttachmentRepo->findByLessonAndAttachment($lessonId, $att2->getAttachmentId()));
	}

	/**
	 * @throws DomainException
	 */
	public function testAddSameAttachmentToDifferentLessons(): void
	{
		$attachmentId = $this->uuidProvider->generate();
		$att = new Attachment($attachmentId, 'common', null, '/tmp/common', 'bin');
		$this->attachmentRepository->store($att);
		$lesson1 = new Lesson($this->uuidProvider->generate(), new \DateTimeImmutable(), 0, 0, $this->uuidProvider->generate(), null, null);
		$lesson2 = new Lesson($this->uuidProvider->generate(), new \DateTimeImmutable(), 0, 0, $this->uuidProvider->generate(), null, null);
		$this->lessonRepository->store($lesson1);
		$this->lessonRepository->store($lesson2);

		$id1 = $this->service->addAttachmentToLesson($lesson1->getLessonId(), $attachmentId);
		$id2 = $this->service->addAttachmentToLesson($lesson2->getLessonId(), $attachmentId);

		$this->assertNotEquals($id1->toString(), $id2->toString());
		$this->assertNotNull($this->lessonAttachmentRepo->findByLessonAndAttachment($lesson1->getLessonId(), $attachmentId));
		$this->assertNotNull($this->lessonAttachmentRepo->findByLessonAndAttachment($lesson2->getLessonId(), $attachmentId));
	}

	/**
	 * @throws DomainException
	 */
	public function testRemoveOneOfMultipleAttachmentsLeavesOthersIntact(): void
	{
		$lessonId = $this->uuidProvider->generate();
		$lesson = new Lesson($lessonId, new \DateTimeImmutable(), 0, 0, $this->uuidProvider->generate(), null, null);
		$this->lessonRepository->store($lesson);
		$assoc1 = new LessonAttachment($this->uuidProvider->generate(), $lessonId, $this->uuidProvider->generate());
		$assoc2 = new LessonAttachment($this->uuidProvider->generate(), $lessonId, $this->uuidProvider->generate());
		$this->lessonAttachmentRepo->store($assoc1);
		$this->lessonAttachmentRepo->store($assoc2);

		$this->service->removeAttachmentFromLesson($lessonId, $assoc1->getAttachmentId());

		$this->assertNull($this->lessonAttachmentRepo->findByLessonAndAttachment($lessonId, $assoc1->getAttachmentId()));
		$this->assertNotNull($this->lessonAttachmentRepo->findByLessonAndAttachment($lessonId, $assoc2->getAttachmentId()));
	}

	public function testAddAttachmentAlreadyExistsPrecedesOtherChecks(): void
	{
		$lessonId = $this->uuidProvider->generate();
		$attachmentId = $this->uuidProvider->generate();
		$existing = new LessonAttachment($this->uuidProvider->generate(), $lessonId, $attachmentId);
		$this->lessonAttachmentRepo->store($existing);

		$this->expectException(DomainException::class);
		$this->expectExceptionCode(DomainException::LESSON_ATTACHMENT_ALREADY_EXISTS);

		$this->service->addAttachmentToLesson($lessonId, $attachmentId);
	}
}

