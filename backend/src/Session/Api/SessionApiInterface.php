<?php
declare(strict_types=1);

namespace App\Session\Api;

use App\Common\Exception\AppException;
use App\Session\App\Provider\Data\SessionUser;

interface SessionApiInterface
{
	/**
	 * @throws AppException
	 */
	public function getCurrentUser(): SessionUser;
}