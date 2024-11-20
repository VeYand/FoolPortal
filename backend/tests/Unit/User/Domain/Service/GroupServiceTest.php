<?php
declare(strict_types=1);

namespace App\Tests\Unit\User\Domain\Service;

use App\Common\Uuid\UuidProvider;
use App\Common\Uuid\UuidProviderInterface;
use App\Tests\Unit\Common\MockEventPublisher;
//use App\Tests\Unit\User\Infrastructure\GroupInMemoryRepository;
use App\Tests\Unit\User\Infrastructure\GroupMemberInMemoryRepository;
use App\User\Domain\Exception\DomainException;
use App\User\Domain\Model\Group;
use App\User\Domain\Model\GroupMember;
use App\User\Domain\Repository\GroupMemberRepositoryInterface;
use App\User\Domain\Repository\GroupRepositoryInterface;
use App\User\Domain\Service\GroupService;
use PHPUnit\Framework\TestCase;

class GroupServiceTest extends TestCase
{
	private GroupService $groupService;
	private GroupRepositoryInterface $groupRepository;
	private GroupMemberRepositoryInterface $groupMemberRepository;
	private UuidProviderInterface $uuidProvider;
	private MockEventPublisher $eventPublisher;

	protected function setUp(): void
	{
		$this->groupRepository = new GroupInMemoryRepository();
		$this->groupMemberRepository = new GroupMemberInMemoryRepository();
		$this->uuidProvider = new UuidProvider();
		$this->eventPublisher = new MockEventPublisher();
		$this->groupService = new GroupService(
			$this->groupRepository,
			$this->uuidProvider,
			$this->groupMemberRepository,
			$this->eventPublisher,
		);
	}

	public function testCreateGroup(): void
	{
		$groupName = 'Test Group';
		$groupId = $this->groupService->create($groupName);

		$group = $this->groupRepository->find($groupId);
		$this->assertNotNull($group);
		$this->assertEquals($groupName, $group->getName());
	}

	/**
	 * @throws DomainException
	 */
	public function testUpdateGroup(): void
	{
		$groupName = 'Test Group';
		$newGroupName = 'Updated Group';
		$groupId = $this->groupService->create($groupName);

		$this->groupService->update($groupId, $newGroupName);

		$group = $this->groupRepository->find($groupId);
		$this->assertNotNull($group);
		$this->assertEquals($newGroupName, $group->getName());
	}

	/**
	 * @throws DomainException
	 */
	public function testUpdateGroupNotFound(): void
	{
		$groupId = 'non-existent-group';
		$newGroupName = 'Updated Group';

		$this->expectException(DomainException::class);
		$this->expectExceptionCode(DomainException::GROUP_NOT_FOUND);

		$this->groupService->update($groupId, $newGroupName);
	}

	public function testDeleteGroup(): void
	{
		$groupName = 'Test Group';
		$groupId = $this->groupService->create($groupName);

		$this->groupService->delete($groupId);

		$group = $this->groupRepository->find($groupId);
		$this->assertNull($group);
	}

	public function testDeleteGroupNotFound(): void
	{
		$groupId = 'non-existent-group';

		$this->groupService->delete($groupId);

		// No exception should be thrown if the group does not exist
		$this->addToAssertionCount(1);
	}

	public function testDeleteGroupWithMembers(): void
	{
		$groupName = 'Test Group';
		$groupId = $this->groupService->create($groupName);

		// Add a member to the group
		$userId = 'user1';
		$groupMemberId = $this->uuidProvider->generate();
		$this->groupMemberRepository->store(new GroupMember($groupMemberId, $groupId, $userId));

		$this->groupService->delete($groupId);

		$group = $this->groupRepository->find($groupId);
		$this->assertNull($group);

		$groupMember = $this->groupMemberRepository->find($groupId, $userId);
		$this->assertNull($groupMember);
	}

	public function testGroupDeletedEvent(): void
	{
		$groupName = 'Test Group';
		$groupId = $this->groupService->create($groupName);

		$eventPublisher = $this->createMock(EventPublisherInterface::class);
		$eventPublisher->expects($this->once())
			->method('publish')
			->with($this->isInstanceOf(GroupDeletedEvent::class));

		$this->groupService = new GroupService(
			$this->groupRepository,
			$this->uuidProvider,
			$this->groupMemberRepository,
			$eventPublisher,
		);

		$this->groupService->delete($groupId);
	}

	/**
	 * @throws DomainException
	 */
	public function testCreateGroupWithEmptyName(): void
	{
		$groupName = '';

		$this->expectException(DomainException::class);
		$this->expectExceptionCode(DomainException::INTERNAL);

		$this->groupService->create($groupName);
	}

	/**
	 * @throws DomainException
	 */
	public function testUpdateGroupWithEmptyName(): void
	{
		$groupName = 'Test Group';
		$groupId = $this->groupService->create($groupName);
		$newGroupName = '';

		$this->expectException(DomainException::class);
		$this->expectExceptionCode(DomainException::INTERNAL);

		$this->groupService->update($groupId, $newGroupName);
	}
}
