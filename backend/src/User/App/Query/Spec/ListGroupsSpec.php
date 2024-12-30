<?php
declare (strict_types=1);

namespace App\User\App\Query\Spec;

readonly class ListGroupsSpec
{
	/**
	 * @param string[]|null $groupIds
	 */
	public function __construct(
		public ?array $groupIds = null,
	)
	{
	}
}