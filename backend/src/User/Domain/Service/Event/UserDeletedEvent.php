<?php
declare(strict_types=1);

namespace App\User\Domain\Service\Event;

readonly class UserDeletedEvent implements UserDeletedEventInterface
{
	/**
	 * @param string[] $userIds
	 */
	public function __construct(
		private array $userIds,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function getUserIds(): array
	{
		return $this->userIds;
	}
}