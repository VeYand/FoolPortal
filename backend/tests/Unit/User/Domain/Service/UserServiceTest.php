<?php
declare(strict_types=1);

namespace App\Tests\Unit\User\Domain\Service;

use App\Common\Uuid\UuidProvider;
use App\Common\Uuid\UuidProviderInterface;
use App\Tests\Unit\User\Domain\Infrastructure\GroupMemberInMemoryRepository;
use App\Tests\Unit\User\Domain\Infrastructure\MockEventPublisher;
use App\Tests\Unit\User\Domain\Infrastructure\MockImageUploader;
use App\Tests\Unit\User\Domain\Infrastructure\UserInMemoryRepository;
use App\User\Domain\Exception\DomainException;
use App\User\Domain\Model\GroupMember;
use App\User\Domain\Model\UserRole;
use App\User\Domain\Repository\GroupMemberRepositoryInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Service\Event\UserDeletedEvent;
use App\User\Domain\Service\ImageUploaderInterface;
use App\User\Domain\Service\Input\CreateUserInput;
use App\User\Domain\Service\Input\UpdateUserInput;
use App\User\Domain\Service\PasswordHasherInterface;
use App\User\Domain\Service\UserService;
use App\User\Infrastructure\Hasher\PasswordHasher;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
	private UserService $userService;
	private UserRepositoryInterface $userRepository;
	private GroupMemberRepositoryInterface $groupMemberRepository;
	private UuidProviderInterface $uuidProvider;
	private ImageUploaderInterface $imageUploader;
	private PasswordHasherInterface $passwordHasher;
	private MockEventPublisher $eventPublisher;


	protected function setUp(): void
	{
		$this->userRepository = new UserInMemoryRepository();
		$this->groupMemberRepository = new GroupMemberInMemoryRepository();
		$this->uuidProvider = new UuidProvider();
		$this->imageUploader = new MockImageUploader();
		$this->passwordHasher = new PasswordHasher();
		$this->eventPublisher = new MockEventPublisher();
		$this->userService = new UserService(
			$this->userRepository,
			$this->groupMemberRepository,
			$this->uuidProvider,
			$this->imageUploader,
			$this->passwordHasher,
			$this->eventPublisher,
		);
	}

	/**
	 * @throws DomainException
	 */
	public function testCreateUserWithoutImage(): void
	{
		$input = new CreateUserInput(
			'John',
			'Doe',
			'Middle',
			UserRole::STUDENT,
			null,
			'john@example.com',
			'password123',
		);
		$userId = $this->userService->create($input);

		$user = $this->userRepository->find($userId);
		$this->assertNotNull($user);
		$this->assertEquals('John', $user->getFirstName());
		$this->assertEquals('Doe', $user->getLastName());
		$this->assertEquals('Middle', $user->getPatronymic());
		$this->assertEquals(UserRole::STUDENT, $user->getRole());
		$this->assertNull($user->getImagePath());
		$this->assertEquals('john@example.com', $user->getEmail());
		$this->assertNotEquals('password123', $user->getPassword());
	}

	/**
	 * @throws DomainException
	 */
	public function testCreateUserWithImage(): void
	{
		$input = new CreateUserInput(
			'Alice',
			'Smith',
			'Middle',
			UserRole::STUDENT,
			'base64data',
			'alice@example.com',
			'adminpass',
		);
		$userId = $this->userService->create($input);

		$user = $this->userRepository->find($userId);
		$this->assertNotNull($user);
		$this->assertEquals('path', $user->getImagePath());
	}

	/**
	 * @throws DomainException
	 */
	public function testCreateUserDuplicateEmailThrowsException(): void
	{
		$existingInput = new CreateUserInput(
			'Bob',
			'Builder',
			'',
			UserRole::STUDENT,
			null,
			'bob@example.com',
			'buildit',
		);
		$this->userService->create($existingInput);

		$this->expectException(DomainException::class);
		$this->expectExceptionCode(DomainException::EMAIL_IS_NOT_UNIQUE);
		$duplicateInput = new CreateUserInput(
			'Robert',
			'Builder',
			'',
			UserRole::STUDENT,
			null,
			'bob@example.com',
			'another',
		);
		$this->userService->create($duplicateInput);
	}

	/**
	 * @throws DomainException
	 */
	public function testUpdateUser(): void
	{
		$createInput = new CreateUserInput(
			'Charlie',
			'Chaplin',
			'',
			UserRole::STUDENT,
			null,
			'charlie@example.com',
			'funny',
		);
		$userId = $this->userService->create($createInput);

		$updateInput = new UpdateUserInput(
			$userId,
			'Charles',
			'Chaplin',
			'Sir',
			UserRole::OWNER,
			'newbase64',
			'charles@example.com',
			'newpass',
		);

		$this->userService->update($updateInput);

		$user = $this->userRepository->find($userId);
		$this->assertEquals('Charles', $user->getFirstName());
		$this->assertEquals('Sir', $user->getPatronymic());
		$this->assertEquals(UserRole::OWNER, $user->getRole());
		$this->assertEquals('path', $user->getImagePath());
		$this->assertEquals('charles@example.com', $user->getEmail());
		$this->assertNotEquals('newpass', $user->getPassword());
	}

	/**
	 * @throws DomainException
	 */
	public function testUpdateAllUserFields(): void
	{
		$createInput = new CreateUserInput(
			'Ivan',
			'Petrov',
			'Ivanovich',
			UserRole::STUDENT,
			'initialbase64',
			'ivan@example.com',
			'initialpass',
		);
		$userId = $this->userService->create($createInput);

		$updateInput = new UpdateUserInput(
			$userId,
			'Igor',
			'Sidorov',
			'Sergeevich',
			UserRole::OWNER,
			'updatedbase64',
			'igor@example.com',
			'updatedpass',
		);

		$this->userService->update($updateInput);

		$user = $this->userRepository->find($userId);
		$this->assertEquals('Igor', $user->getFirstName());
		$this->assertEquals('Sidorov', $user->getLastName());
		$this->assertEquals('Sergeevich', $user->getPatronymic());
		$this->assertEquals(UserRole::OWNER, $user->getRole());
		$this->assertEquals('path', $user->getImagePath());
		$this->assertEquals('igor@example.com', $user->getEmail());
		$this->assertNotEquals('updatedpass', $user->getPassword());
	}

	public function testUpdateUserNotFoundThrowsException(): void
	{
		$randomId = $this->uuidProvider->generate();
		$updateInput = new UpdateUserInput(
			$randomId,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
		);

		$this->expectException(DomainException::class);
		$this->expectExceptionCode(DomainException::USER_NOT_FOUND);
		$this->userService->update($updateInput);
	}

	/**
	 * @throws DomainException
	 */
	public function testDeleteUser(): void
	{
		$createInput = new CreateUserInput(
			'Dave',
			'Developer',
			'',
			UserRole::STUDENT,
			'base64img',
			'dave@example.com',
			'code',
		);
		$userId = $this->userService->create($createInput);

		$groupMember = new GroupMember(
			$this->uuidProvider->generate(),
			$this->uuidProvider->generate(),
			$userId,
		);
		$this->groupMemberRepository->store($groupMember);

		$this->userService->delete($userId);

		$this->assertNull($this->userRepository->find($userId));
		$this->assertEmpty($this->groupMemberRepository->findByUsers([$userId]));
		$events = $this->eventPublisher->getEvents();
		$this->assertCount(1, $events);
		$this->assertInstanceOf(
			UserDeletedEvent::class,
			$events[0],
		);
		$this->assertEquals(
			[$userId],
			$events[0]->getUserIds(),
		);
	}

	/**
	 * @throws DomainException
	 */
	public function testDeleteUserNotExisting(): void
	{
		$randomId = $this->uuidProvider->generate();
		$this->userService->delete($randomId);
		$this->assertEmpty($this->userRepository->findAll());
		$events = $this->eventPublisher->getEvents();
		$this->assertCount(0, $events);
	}
}