<?php
declare(strict_types=1);

namespace App\Session\App\Provider\Data;

use App\User\Domain\Model\UserRole;

readonly class SessionUser
{
	public function __construct(
		public string   $id,
		public string   $email,
		public UserRole $role,
	)
	{
	}
}