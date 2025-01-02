<?php
declare(strict_types=1);

namespace App\Lesson\Infrastructure\Adapter;

use App\Common\Uuid\UuidInterface;
use App\Lesson\App\Adapter\Data\UserRole;
use App\Lesson\App\Adapter\SessionAdapterInterface;
use App\Session\Api\SessionApiInterface;

readonly class SessionAdapter implements SessionAdapterInterface
{
	public function __construct(
		private SessionApiInterface $sessionApi,
	)
	{
	}

	public function getCurrentUserId(): UuidInterface
	{
		return $this->sessionApi->getCurrentUser()->id;
	}

	public function getCurrentUserRole(): UserRole
	{
		return UserRole::from($this->sessionApi->getCurrentUser()->role->value);
	}
}