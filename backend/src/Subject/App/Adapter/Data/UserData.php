<?php
declare(strict_types=1);

namespace App\Subject\App\Adapter\Data;

readonly class UserData
{
	public function __construct(
		public string   $userId,
		public UserRole $role,
	)
	{
	}
}