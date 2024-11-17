<?php
declare(strict_types=1);

namespace App\User\Domain\Service\Event;

interface UserDeletedEventInterface
{
	/**
	 * @return string[]
	 */
	public function getUserIds(): array;
}