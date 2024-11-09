<?php
declare(strict_types=1);

namespace App\Security\App\Adapter\Data;

readonly class UserData
{
	public function __construct(
		public string   $userId,
		public UserRole $role,
		public string   $email,
		public string   $password,
	)
	{
	}
}