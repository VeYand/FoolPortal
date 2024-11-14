<?php
declare(strict_types=1);

namespace App\Session\Api;

use App\Session\Api\Exception\ApiException;
use App\Session\App\Provider\Data\SessionUser;

interface SessionApiInterface
{
	/**
	 * @throws ApiException
	 */
	public function getCurrentUser(): SessionUser; // TODO // Как обработать
}