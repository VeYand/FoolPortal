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
use App\User\Domain\Model\UserRole;
use App\User\Domain\Repository\GroupMemberRepositoryInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Service\ImageUploaderInterface;
use App\User\Domain\Service\Input\CreateUserInput;
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
}