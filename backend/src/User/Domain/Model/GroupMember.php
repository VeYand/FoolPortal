<?php
declare(strict_types=1);

namespace App\User\Domain\Model;

use App\Common\Uuid\UuidInterface;

readonly class GroupMember
{
	public function __construct(
		private UuidInterface $groupMemberId,
		private UuidInterface $groupId,
		private UuidInterface $userId,
	)
	{
	}

	public function getGroupMemberId(): UuidInterface
	{
		return $this->groupMemberId;
	}

	public function getGroupId(): UuidInterface
	{
		return $this->groupId;
	}

	public function getUserId(): UuidInterface
	{
		return $this->userId;
	}
}