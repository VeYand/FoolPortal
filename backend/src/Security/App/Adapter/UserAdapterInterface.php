<?php
declare(strict_types=1);

namespace App\Security\App\Adapter;

use App\Security\App\Adapter\Data\UserData;
use App\Security\App\Exception\AppException;

interface UserAdapterInterface
{
	/**
	 * @throws AppException
	 */
	public function getUserByEmail(string $email): UserData;
}