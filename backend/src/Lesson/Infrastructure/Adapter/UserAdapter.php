<?php
declare(strict_types=1);

namespace App\Lesson\Infrastructure\Adapter;

use App\Lesson\App\Adapter\UserAdapterInterface;
use App\User\Api\UserApiInterface;
use App\User\App\Query\Data\GroupData;
use App\User\App\Query\Spec\ListGroupsSpec;

readonly class UserAdapter implements UserAdapterInterface
{
	public function __construct(
		private UserApiInterface $userApi,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function listUserGroupIds(string $userId): array
	{
		$groups = $this->userApi->listGroups(new ListGroupsSpec(userIds: [$userId]));
		return array_map(static fn(GroupData $group) => $group->groupId, $groups);
	}
}