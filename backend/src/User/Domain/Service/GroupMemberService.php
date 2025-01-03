<?php
declare(strict_types=1);

namespace App\User\Domain\Service;

use App\Common\Uuid\UuidInterface;
use App\Common\Uuid\UuidProviderInterface;
use App\User\Domain\Exception\DomainException;
use App\User\Domain\Model\GroupMember;
use App\User\Domain\Repository\GroupMemberRepositoryInterface;
use App\User\Domain\Repository\GroupReadRepositoryInterface;
use App\User\Domain\Repository\UserReadRepositoryInterface;

readonly class GroupMemberService
{
	public function __construct(
		private GroupMemberRepositoryInterface $groupMemberRepository,
		private GroupReadRepositoryInterface   $groupRepository,
		private UserReadRepositoryInterface    $userRepository,
		private UuidProviderInterface          $uuidProvider,
	)
	{
	}

	/**
	 * @throws DomainException
	 */
	public function createGroupMembers(UuidInterface $groupId, UuidInterface $userId): UuidInterface
	{
		$this->assertGroupExists($groupId);
		$this->assertUserExists($userId);
		$this->assertGroupMemberNotExists($groupId, $userId);

		$groupMember = new GroupMember(
			$this->uuidProvider->generate(),
			$groupId,
			$userId,
		);

		return $this->groupMemberRepository->store($groupMember);
	}

	public function deleteGroupMembers(UuidInterface $groupId, UuidInterface $userId): void
	{
		$groupMember = $this->groupMemberRepository->find($groupId, $userId);

		if (!is_null($groupMember))
		{
			$this->groupMemberRepository->delete([$groupMember]);
		}
	}

	/**
	 * @throws DomainException
	 */
	private function assertUserExists(UuidInterface $userId): void
	{
		$user = $this->userRepository->find($userId);

		if (is_null($user))
		{
			throw new DomainException('User not found', DomainException::USER_NOT_FOUND);
		}
	}

	/**
	 * @throws DomainException
	 */
	private function assertGroupExists(UuidInterface $groupId): void
	{
		$group = $this->groupRepository->find($groupId);

		if (is_null($group))
		{
			throw new DomainException('Group not found', DomainException::GROUP_NOT_FOUND);
		}
	}

	/**
	 * @throws DomainException
	 */
	private function assertGroupMemberNotExists(UuidInterface $groupId, UuidInterface $userId): void
	{
		$groupMember = $this->groupMemberRepository->find($groupId, $userId);

		if (!is_null($groupMember))
		{
			throw new DomainException('Group member already exists', DomainException::GROUP_MEMBER_ALREADY_EXISTS);
		}
	}
}