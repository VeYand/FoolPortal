<?php
declare(strict_types=1);

namespace App\User\Domain\Service;

use App\Common\Exception\DomainException;

interface PasswordHasherInterface
{
	/**
	 * @throws DomainException
	 */
	public function hash(string $plainPassword): string;
}