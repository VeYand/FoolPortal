<?php
declare(strict_types=1);

namespace App\Session\App\Provider\Data;

use App\Common\Uuid\UuidInterface;
use App\User\Domain\Model\UserRole;

readonly class SessionUser
{
	public function __construct(
		public UuidInterface $id,
		public string        $email,
		public UserRole      $role,
	)
	{
	}
}