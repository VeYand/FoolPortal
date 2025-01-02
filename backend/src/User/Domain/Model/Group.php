<?php
declare(strict_types=1);

namespace App\User\Domain\Model;

use App\Common\Uuid\UuidInterface;

class Group
{
	public function __construct(
		private readonly UuidInterface $groupId,
		private string                 $name,
	)
	{
	}

	public function getGroupId(): UuidInterface
	{
		return $this->groupId;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}
}