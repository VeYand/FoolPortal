<?php
declare(strict_types=1);

namespace App\Tests\Unit\User\Domain\Service;

use App\Common\Uuid\UuidProvider;
use App\Common\Uuid\UuidProviderInterface;
use App\Tests\Unit\Common\MockEventPublisher;
use App\Tests\Unit\User\Infrastructure\GroupMemberInMemoryRepository;
use App\Tests\Unit\User\Infrastructure\MockImageUploader;
use App\Tests\Unit\User\Infrastructure\UserInMemoryRepository;
use App\User\Domain\Exception\DomainException;
use App\User\Domain\Model\GroupMember;
use App\User\Domain\Model\UserRole;
use App\User\Domain\Repository\GroupMemberRepositoryInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
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


	protected function setUp(): void
	{
		$this->userRepository = new UserInMemoryRepository();
		$this->groupMemberRepository = new GroupMemberInMemoryRepository();
		$this->uuidProvider = new UuidProvider();
		$this->imageUploader = new MockImageUploader();
		$this->passwordHasher = new PasswordHasher();
		$this->userService = new UserService(
			$this->userRepository,
			$this->groupMemberRepository,
			$this->uuidProvider,
			$this->imageUploader,
			$this->passwordHasher,
			new MockEventPublisher(),
		);
	}

	/**
	 * @throws DomainException
	 */
	public function testCreateUser(): void
	{
		$input = new CreateUserInput(
			'firstName',
			'lastName',
			null,
			UserRole::ADMIN,
			null,
			'email@gmail.com',
			'password',
		);

		$this->userService->create($input);

		$this->assertCount(1, $this->userRepository->findAll());
	}

	/**
	 * @throws DomainException
	 */
	public function testCreateUserEmailIsAlreadyTaken(): void
	{
		$input = new CreateUserInput(
			'firstName',
			'lastName',
			null,
			UserRole::ADMIN,
			null,
			'email@gmail.com',
			'password',
		);
		$this->userService->create($input);

		$this->expectException(DomainException::class);
		$this->expectExceptionCode(DomainException::EMAIL_IS_NOT_UNIQUE);

		$this->userService->create($input);
	}
	/**
	 * @throws DomainException
	 */
	public function testUpdateUser(): void
	{
		$createInput = new CreateUserInput(
			'firstName',
			'lastName',
			null,
			UserRole::ADMIN,
			null,
			'email@gmail.com',
			'password',
		);
		$userId = $this->userService->create($createInput);

		$updateInput = new UpdateUserInput(
			$userId,
			'newFirstName',
			'newLastName',
			'newPatronymic',
			UserRole::STUDENT,
			null,
			'newemail@gmail.com',
			'newpassword',
		);
		$this->userService->update($updateInput);

		$user = $this->userRepository->find($userId);
		$this->assertEquals('newFirstName', $user->getFirstName());
		$this->assertEquals('newLastName', $user->getLastName());
		$this->assertEquals('newPatronymic', $user->getPatronymic());
		$this->assertEquals(UserRole::STUDENT, $user->getRole());
		$this->assertEquals('newemail@gmail.com', $user->getEmail());
		$this->assertTrue($this->passwordHasher->verify('newpassword', $user->getPassword()));
	}

	/**
	 * @throws DomainException
	 */
	public function testUpdateUserNotFound(): void
	{
		$updateInput = new UpdateUserInput(
			'non-existent-user-id',
			'newFirstName',
			'newLastName',
			'newPatronymic',
			UserRole::ADMIN,
			null,
			'newemail@gmail.com',
			'newpassword',
		);

		$this->expectException(DomainException::class);
		$this->expectExceptionCode(DomainException::USER_NOT_FOUND);

		$this->userService->update($updateInput);
	}

	/**
	 * @throws DomainException
	 */
	public function testUpdateUserPartialData(): void
	{
		$createInput = new CreateUserInput(
			'firstName',
			'lastName',
			null,
			UserRole::ADMIN,
			null,
			'email@gmail.com',
			'password',
		);
		$userId = $this->userService->create($createInput);

		$updateInput = new UpdateUserInput(
			$userId,
			'newFirstName',
			null,
			null,
			null,
			null,
			null,
			null,
		);
		$this->userService->update($updateInput);

		$user = $this->userRepository->find($userId);
		$this->assertEquals('newFirstName', $user->getFirstName());
		$this->assertEquals('lastName', $user->getLastName());
		$this->assertEquals(null, $user->getPatronymic());
		$this->assertEquals(UserRole::ADMIN, $user->getRole());
		$this->assertEquals('email@gmail.com', $user->getEmail());
	}

	/**
	 * @throws DomainException
	 */
	public function testDeleteUser(): void
	{
		$createInput = new CreateUserInput(
			'firstName',
			'lastName',
			null,
			UserRole::ADMIN,
			null,
			'email@gmail.com',
			'password',
		);
		$userId = $this->userService->create($createInput);

		$this->userService->delete($userId);

		$this->assertNull($this->userRepository->find($userId));
	}

	/**
	 * @throws DomainException
	 */
	public function testDeleteUserNotFound(): void
	{
		$this->userService->delete('non-existent-user-id');

		// No exception should be thrown if the user does not exist
		$this->addToAssertionCount(1);
	}

	/**
	 * @throws DomainException
	 */
	public function testDeleteUserWithGroups(): void
	{
		$createInput = new CreateUserInput(
			'firstName',
			'lastName',
			null,
			UserRole::ADMIN,
			null,
			'email@gmail.com',
			'password',
		);
		$userId = $this->userService->create($createInput);

		// Add user to a group
		$groupMember = new GroupMember($userId, 'groupId');
		$this->groupMemberRepository->store($groupMember);

		$this->userService->delete($userId);

		$this->assertNull($this->userRepository->find($userId));
		$this->assertEmpty($this->groupMemberRepository->findByUser($userId));
	}

	/**
	 * @throws DomainException
	 */
	public function testImageUpload(): void
	{
		$base64ImageData = base64_encode(file_get_contents(__DIR__ . '/test_image.jpg'));
		$createInput = new CreateUserInput(
			'firstName',
			'lastName',
			null,
			UserRole::ADMIN,
			$base64ImageData,
			'email@gmail.com',
			'password',
		);
		$userId = $this->userService->create($createInput);

		$user = $this->userRepository->find($userId);
		$this->assertNotNull($user->getImagePath());
	}

	/**
	 * @throws DomainException
	 */
	public function testImageRemoval(): void
	{
		$base64ImageData = base64_encode(file_get_contents(__DIR__ . '/test_image.jpg'));
		$createInput = new CreateUserInput(
			'firstName',
			'lastName',
			null,
			UserRole::ADMIN,
			$base64ImageData,
			'email@gmail.com',
			'password',
		);
		$userId = $this->userService->create($createInput);

		$this->userService->delete($userId);

		$user = $this->userRepository->find($userId);
		$this->assertNull($user);
		$this->assertNull($this->imageUploader->getImagePath($userId));
	}


	/**
	 * @throws DomainException
	 */
	public function testPasswordHashing(): void
	{
		$createInput = new CreateUserInput(
			'firstName',
			'lastName',
			null,
			UserRole::ADMIN,
			null,
			'email@gmail.com',
			'password',
		);
		$userId = $this->userService->create($createInput);

		$user = $this->userRepository->find($userId);
		$this->assertTrue($this->passwordHasher->verify('password', $user->getPassword()));
	}

	/**
	 * @throws DomainException
	 */
	//public function testUserDeletedEvent(): void
	//{
	//	$createInput = new CreateUserInput(
	//		'firstName',
	//		'lastName',
	//		null,
	//		UserRole::ADMIN,
	//		null,
	//		'email@gmail.com',
	//		'password',
	//	);
	//	$userId = $this->userService->create($createInput);

		//$eventPublisher = $this->createMock(EventPublisherInterface::class);
		//$eventPublisher->expects($this->once())
		//	->method('publish')
		//	->with($this->isInstanceOf(UserDeletedEvent::class));

		//$this->userService = new UserService(
		//	$this->userRepository,
		//	$this->groupMemberRepository,
		//	$this->uuidProvider,
			//$this->imageUploader,
			//$this->passwordHasher,
			//$eventPublisher,
		//);

		//$this->userService->delete($userId);
//	}

}