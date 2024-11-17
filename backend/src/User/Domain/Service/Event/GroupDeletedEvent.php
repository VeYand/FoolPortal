<?php
declare(strict_types=1);

namespace App\User\Domain\Service\Event;

readonly class GroupDeletedEvent implements GroupDeletedEventInterface
{
	public function __construct(
		private string $groupId,
	)
	{
	}

	public function getGroupId(): string
	{
		return $this->groupId;
	}
}