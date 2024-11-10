<?php
declare(strict_types=1);

namespace App\User\Infrastructure\Query;

use App\User\App\Query\Data\GroupData;
use App\User\App\Query\GroupQueryServiceInterface;
use App\User\Domain\Model\Group;
use App\User\Domain\Repository\GroupReadRepositoryInterface;

readonly class GroupQueryService implements GroupQueryServiceInterface
{
	public function __construct(
		private GroupReadRepositoryInterface $groupReadRepository,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function listAllGroups(): array
	{
		$groups = $this->groupReadRepository->findAll();

		return array_map(
			static fn(Group $group) => new GroupData(
				$group->getGroupId(),
				$group->getName(),
			),
			$groups,
		);
	}
}