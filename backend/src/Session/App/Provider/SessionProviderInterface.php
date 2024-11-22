<?php
declare(strict_types=1);

namespace App\Session\App\Provider;

use App\Session\App\Exception\AppException;
use App\Session\App\Provider\Data\SessionUser;

interface SessionProviderInterface
{
	/**
	 * @throws AppException
	 */
	public function getCurrentUser(): SessionUser;
}
