<?php
declare(strict_types=1);

namespace App\User\Domain\Service;

use App\Common\Event\EventPublisherInterface;
use App\Common\Uuid\UuidProviderInterface;
use App\User\Domain\Exception\DomainException;
use App\User\Domain\Model\Group;
use App\User\Domain\Repository\GroupMemberRepositoryInterface;
use App\User\Domain\Repository\GroupRepositoryInterface;
use App\User\Domain\Service\Event\GroupDeletedEvent;

readonly class GroupService
{
	public function __construct(
		private GroupRepositoryInterface       $groupRepository,
		private UuidProviderInterface          $uuidProvider,
		private GroupMemberRepositoryInterface $groupMemberRepository,
		private EventPublisherInterface        $eventPublisher,
	)
	{
	}

	public function create(string $groupName): string
	{
		$group = new Group(
			$this->uuidProvider->generate(),
			$groupName,
		);

		return $this->groupRepository->store($group);
	}

	/**
	 * @throws DomainException
	 */
	public function update(string $groupId, string $groupName): void
	{
		$group = $this->groupRepository->find($groupId);

		if (is_null($group))
		{
			throw new DomainException('Group not found', DomainException::GROUP_NOT_FOUND);
		}

		$group->setName($groupName);
		$this->groupRepository->store($group);
	}

	public function delete(string $groupId): void
	{
		$group = $this->groupRepository->find($groupId);

		if (!is_null($group))
		{
			$groupMembers = $this->groupMemberRepository->findByGroup($group->getGroupId());
			$this->groupMemberRepository->delete($groupMembers);
			$this->groupRepository->delete($group);
			$this->eventPublisher->publish(new GroupDeletedEvent([$groupId]));
		}
	}
}