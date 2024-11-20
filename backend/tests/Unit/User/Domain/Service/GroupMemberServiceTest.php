<?php
declare(strict_types=1);

namespace App\Tests\Unit\User\Domain\Service;

use App\Common\Uuid\UuidProvider;
use App\Common\Uuid\UuidProviderInterface;
use App\Tests\Unit\User\Infrastructure\GroupInMemoryRepository;
use App\Tests\Unit\User\Infrastructure\GroupMemberInMemoryRepository;
use App\Tests\Unit\User\Infrastructure\UserInMemoryRepository;
use App\User\Domain\Exception\DomainException;
use App\User\Domain\Model\Group;
use App\User\Domain\Model\User;
use App\User\Domain\Model\UserRole;
use App\User\Domain\Repository\GroupMemberRepositoryInterface;
use App\User\Domain\Repository\GroupReadRepositoryInterface;
use App\User\Domain\Repository\UserReadRepositoryInterface;
use App\User\Domain\Service\GroupMemberService;
use PHPUnit\Framework\TestCase;

class GroupMemberServiceTest extends TestCase
{
	private GroupMemberService $groupMemberService;
	private GroupMemberRepositoryInterface $groupMemberRepository;
	private GroupReadRepositoryInterface $groupRepository;
	private UserReadRepositoryInterface $userRepository;
	private UuidProviderInterface $uuidProvider;

	protected function setUp(): void
	{
		$this->groupMemberRepository = new GroupMemberInMemoryRepository();
		$this->groupRepository = new GroupInMemoryRepository();
		$this->userRepository = new UserInMemoryRepository();
		$this->uuidProvider = new UuidProvider();
		$this->groupMemberService = new GroupMemberService(
			$this->groupMemberRepository,
			$this->groupRepository,
			$this->userRepository,
			$this->uuidProvider,
		);
	}

	/**
	 * @throws DomainException
	 */
	public function testAddUserToGroup(): void
	{
		$groupId = 'group1';
		$userId = 'user1';

		$this->groupRepository->store(new Group($groupId, 'Group 1'));
		$this->userRepository->store(new User($userId, 'First', 'Last', null, UserRole::ADMIN, null, 'email@example.com', 'password'));

		$groupMemberId = $this->groupMemberService->addUserToGroup($groupId, $userId);

		$groupMember = $this->groupMemberRepository->find($groupId, $userId);
		$this->assertNotNull($groupMember);
		$this->assertEquals($groupMemberId, $groupMember->getGroupMemberId());
	}

	/**
	 * @throws DomainException
	 */
	public function testAddUserToGroupUserNotFound(): void
	{
		$groupId = 'group1';
		$userId = 'non-existent-user';

		$this->groupRepository->store(new Group($groupId, 'Group 1'));

		$this->expectException(DomainException::class);
		$this->expectExceptionCode(DomainException::USER_NOT_FOUND);

		$this->groupMemberService->addUserToGroup($groupId, $userId);
	}

	/**
	 * @throws DomainException
	 */
	public function testAddUserToGroupGroupNotFound(): void
	{
		$groupId = 'non-existent-group';
		$userId = 'user1';

		$this->userRepository->store(new User($userId, 'First', 'Last', null, UserRole::ADMIN, null, 'email@example.com', 'password'));

		$this->expectException(DomainException::class);
		$this->expectExceptionCode(DomainException::GROUP_NOT_FOUND);

		$this->groupMemberService->addUserToGroup($groupId, $userId);
	}

	/**
	 * @throws DomainException
	 */
	public function testAddUserToGroupGroupMemberAlreadyExists(): void
	{
		$groupId = 'group1';
		$userId = 'user1';

		$this->groupRepository->store(new Group($groupId, 'Group 1'));
		$this->userRepository->store(new User($userId, 'First', 'Last', null, UserRole::ADMIN, null, 'email@example.com', 'password'));

		$this->groupMemberService->addUserToGroup($groupId, $userId);

		$this->expectException(DomainException::class);
		$this->expectExceptionCode(DomainException::GROUP_MEMBER_ALREADY_EXISTS);

		$this->groupMemberService->addUserToGroup($groupId, $userId);
	}

	/**
	 * @throws DomainException
	 */
	public function testRemoveUserFromGroup(): void
	{
		$groupId = 'group1';
		$userId = 'user1';

		$this->groupRepository->store(new Group($groupId, 'Group 1'));
		$this->userRepository->store(new User($userId, 'First', 'Last', null, UserRole::ADMIN, null, 'email@example.com', 'password'));
		$this->groupMemberService->addUserToGroup($groupId, $userId);

		$this->groupMemberService->removeUserFromGroup($userId, $groupId);

		$groupMember = $this->groupMemberRepository->find($groupId, $userId);
		$this->assertNull($groupMember);
	}

	/**
	 * @throws DomainException
	 */
	public function testRemoveUserFromGroupUserNotFound(): void
	{
		$groupId = 'group1';
		$userId = 'non-existent-user';

		$this->groupRepository->store(new Group($groupId, 'Group 1'));

		$this->groupMemberService->removeUserFromGroup($userId, $groupId);

		// No exception should be thrown if the user does not exist
		$this->addToAssertionCount(1);
	}

	/**
	 * @throws DomainException
	 */
	public function testAddUserToGroupWithExistingUser(): void
	{
		$groupId = 'group1';
		$userId = 'user1';

		$this->groupRepository->store(new Group($groupId, 'Group 1'));
		$this->userRepository->store(new User($userId, 'First', 'Last', null, UserRole::ADMIN, null, 'email@example.com', 'password'));

		$groupMemberId = $this->groupMemberService->addUserToGroup($groupId, $userId);

		$groupMember = $this->groupMemberRepository->find($groupId, $userId);
		$this->assertNotNull($groupMember);
		$this->assertEquals($groupMemberId, $groupMember->getGroupMemberId());
	}

	/**
	 * @throws DomainException
	 */
	public function testRemoveUserFromGroupUserNotInGroup(): void
	{
		$groupId = 'group1';
		$userId = 'user1';

		$this->groupRepository->store(new Group($groupId, 'Group 1'));
		$this->userRepository->store(new User($userId, 'First', 'Last', null, UserRole::ADMIN, null, 'email@example.com', 'password'));

		$this->groupMemberService->removeUserFromGroup($userId, $groupId);

		// No exception should be thrown if the user is not in the group
		$this->addToAssertionCount(1);
	}

	/**
	 * @throws DomainException
	 */
	public function testAddUserToGroupWithExistingGroup(): void
	{
		$groupId = 'group1';
		$userId = 'user1';

		$this->groupRepository->store(new Group($groupId, 'Group 1'));
		$this->userRepository->store(new User($userId, 'First', 'Last', null, UserRole::ADMIN, null, 'email@example.com', 'password'));

		$groupMemberId = $this->groupMemberService->addUserToGroup($groupId, $userId);

		$groupMember = $this->groupMemberRepository->find($groupId, $userId);
		$this->assertNotNull($groupMember);
		$this->assertEquals($groupMemberId, $groupMember->getGroupMemberId());
	}

	/**
	 */
	public function testRemoveUserFromGroupGroupNotFound(): void
	{
		$groupId = 'non-existent-group';
		$userId = 'user1';

		$this->userRepository->store(new User($userId, 'First', 'Last', null, UserRole::ADMIN, null, 'email@example.com', 'password'));

		$this->groupMemberService->removeUserFromGroup($userId, $groupId);

		// No exception should be thrown if the group does not exist
		$this->addToAssertionCount(1);
	}
}
