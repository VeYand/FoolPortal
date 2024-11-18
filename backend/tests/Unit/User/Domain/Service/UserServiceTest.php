<?php
declare(strict_types=1);

namespace App\Tests\Unit\User\Domain\Service;

use App\Common\Uuid\UuidProvider;
use App\Common\Uuid\UuidProviderInterface;
use App\Tests\Unit\Common\MockEventPublisher;
use App\Tests\Unit\User\Domain\Infrastructure\GroupMemberInMemoryRepository;
use App\Tests\Unit\User\Domain\Infrastructure\MockImageUploader;
use App\Tests\Unit\User\Domain\Infrastructure\UserInMemoryRepository;
use App\User\Domain\Repository\GroupMemberRepositoryInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Service\ImageUploaderInterface;
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

	public function testCase(): void
	{
		// подготовка данных

		// действие

		// проверка результата
		$this->assertTrue(true);
	}
}