<?php
declare(strict_types=1);

namespace App\User\Infrastructure\Query;

use App\User\App\Query\Data\GroupData;
use App\User\App\Query\GroupQueryServiceInterface;
use App\User\App\Query\Spec\ListGroupsSpec;
use App\User\Domain\Model\Group;
use App\User\Domain\Model\GroupMember;
use App\User\Domain\Repository\GroupReadRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

readonly class GroupQueryService implements GroupQueryServiceInterface
{
	public function __construct(
		private GroupReadRepositoryInterface $groupReadRepository,
		private EntityManagerInterface       $entityManager,
	)
	{
	}

	public function isGroupExists(string $groupId): bool
	{
		$group = $this->groupReadRepository->find($groupId);

		return !is_null($group);
	}

	/**
	 * @inheritDoc
	 */
	public function listGroups(ListGroupsSpec $spec): array
	{
		$qb = $this->entityManager->createQueryBuilder();

		$qb->select('g')
			->from(Group::class, 'g')
			->leftJoin(GroupMember::class, 'gm', 'WITH', 'g.groupId = gm.groupId');

		if (!empty($spec->groupIds))
		{
			$qb->andWhere('g.groupId IN (:groupIds)')
				->setParameter('groupIds', $spec->groupIds);
		}

		if (!empty($spec->userIds))
		{
			$qb->andWhere('gm.userId IN (:userIds)')
				->setParameter('userIds', $spec->userIds);
		}

		$groups = $qb->getQuery()->getResult();
		return array_map(
			static fn(Group $group) => new GroupData(
				$group->getGroupId(),
				$group->getName(),
			),
			$groups,
		);
	}
}