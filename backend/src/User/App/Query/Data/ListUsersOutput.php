<?php
declare(strict_types=1);

namespace App\User\App\Query\Data;

readonly class ListUsersOutput
{
	/**
	 * @param DetailedUserData[] $users
	 * @param int $maxPage
	 */
	public function __construct(
		public array $users,
		public int   $maxPage,
	)
	{
	}
}