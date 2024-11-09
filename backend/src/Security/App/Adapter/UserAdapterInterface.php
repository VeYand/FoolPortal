<?php
declare(strict_types=1);

namespace App\Security\App\Adapter;

use App\Common\Exception\AppException;
use App\Security\App\Adapter\Data\UserData;

interface UserAdapterInterface
{
	/**
	 * @throws AppException
	 */
	public function getUserByEmail(string $email): UserData;
}