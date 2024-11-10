<?php
declare(strict_types=1);

namespace App\User\App\Adapter;

use App\Common\Exception\AppException;

interface SessionAdapterInterface
{
	/**
	 * @throws AppException
	 */
	public function isLoggedAdmin(): bool;
}