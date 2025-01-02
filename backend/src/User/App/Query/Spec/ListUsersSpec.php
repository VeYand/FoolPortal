<?php
declare (strict_types=1);

namespace App\User\App\Query\Spec;

use App\Common\Uuid\UuidInterface;

readonly class ListUsersSpec
{
	/**
	 * @param UuidInterface[]|null $groupIds
	 */
	public function __construct(
		public ?array $groupIds = null,
	)
	{
	}
}