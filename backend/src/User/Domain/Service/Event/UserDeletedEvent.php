<?php
declare(strict_types=1);

namespace App\User\Domain\Service\Event;

readonly class UserDeletedEvent implements UserDeletedEventInterface
{
	public function __construct(
		private string $userId,
	)
	{
	}

	public function getUserId(): string
	{
		return $this->userId;
	}
}