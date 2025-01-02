<?php
declare (strict_types=1);

namespace App\User\App\Query\Spec;

use App\Common\Uuid\UuidInterface;

readonly class ListGroupsSpec
{
	/**
	 * @param UuidInterface[]|null $groupIds
	 * @param UuidInterface[]|null $userIds
	 */
	public function __construct(
		public ?array $groupIds = null,
		public ?array $userIds = null,
	)
	{
	}
}