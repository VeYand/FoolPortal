<?php
declare(strict_types=1);

namespace App\Security\App\Adapter\Data;

use App\Common\Uuid\UuidInterface;

readonly class UserData
{
	public function __construct(
		public UuidInterface $userId,
		public UserRole      $role,
		public string        $email,
		public string        $password,
	)
	{
	}
}