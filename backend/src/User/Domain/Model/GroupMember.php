<?php
declare(strict_types=1);

namespace App\User\Domain\Model;

readonly class GroupMember
{
	public function __construct(
		private string $groupMemberId,
		private string $groupId,
		private string $userId,
	)
	{
	}

	public function getGroupMemberId(): string
	{
		return $this->groupMemberId;
	}

	public function getGroupId(): string
	{
		return $this->groupId;
	}

	public function getUserId(): string
	{
		return $this->userId;
	}
}