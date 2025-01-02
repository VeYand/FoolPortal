<?php
declare(strict_types=1);

namespace App\User\Domain\Service\Event;

use App\Common\Uuid\UuidInterface;

readonly class GroupDeletedEvent implements GroupDeletedEventInterface
{
	/**
	 * @param UuidInterface[] $groupIds
	 */
	public function __construct(
		private array $groupIds,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function getGroupIds(): array
	{
		return $this->groupIds;
	}
}