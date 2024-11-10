<?php
declare(strict_types=1);

namespace App\User\Infrastructure\Adapter;

use App\Common\Exception\AppException;
use App\Session\Api\SessionApiInterface;
use App\User\App\Adapter\SessionAdapterInterface;
use App\User\Domain\Model\UserRole;

readonly class SessionAdapter implements SessionAdapterInterface
{
	public function __construct(
		private SessionApiInterface $sessionApi,
	)
	{
	}

	/**
	 * @throws AppException
	 */
	public function isLoggedAdmin(): bool
	{
		$user = $this->sessionApi->getCurrentUser();

		return $user->role === UserRole::ADMIN || $user->role === UserRole::OWNER;
	}
}