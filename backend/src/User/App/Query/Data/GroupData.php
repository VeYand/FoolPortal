<?php
declare(strict_types=1);

namespace App\User\App\Query\Data;

use App\Common\Uuid\UuidInterface;

readonly class GroupData
{
	public function __construct(
		public UuidInterface $groupId,
		public string        $name,
	)
	{
	}
}