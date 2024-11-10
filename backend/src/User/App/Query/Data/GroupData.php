<?php
declare(strict_types=1);

namespace App\User\App\Query\Data;

readonly class GroupData
{
	public function __construct(
		public string $groupId,
		public string $name,
	)
	{
	}
}