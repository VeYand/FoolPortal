<?php
declare(strict_types=1);

namespace App\Session\Api;

use App\Common\Exception\AppException;
use App\Session\App\Provider\Data\SessionUser;
use App\Session\App\Provider\SessionProviderInterface;

readonly class SessionApi implements SessionApiInterface
{
	public function __construct(
		private SessionProviderInterface $sessionProvider,
	)
	{
	}

	/**
	 * @throws AppException
	 */
	public function getCurrentUser(): SessionUser
	{
		return $this->sessionProvider->getCurrentUser();
	}
}