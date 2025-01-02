<?php
declare(strict_types=1);

namespace App\User\Domain\Service\Event;

use App\Common\Uuid\UuidInterface;

readonly class UserDeletedEvent implements UserDeletedEventInterface
{
	/**
	 * @param UuidInterface[] $userIds
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