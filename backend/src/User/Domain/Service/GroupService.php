<?php
declare(strict_types=1);

namespace App\User\Domain\Service;

use App\Common\Exception\DomainException;
use App\Common\Uuid\UuidProviderInterface;
use App\User\Domain\Model\Group;
use App\User\Domain\Repository\GroupRepositoryInterface;

readonly class GroupService
{
	public function __construct(
		private GroupRepositoryInterface $groupRepository,
		private UuidProviderInterface    $uuidProvider,
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
			throw new DomainException('Group not found');
		}

		$group->setName($groupName);
		$this->groupRepository->store($group);
	}
}