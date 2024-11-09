<?php
declare(strict_types=1);

namespace App\User\Domain\Model;

class Group
{
	public function __construct(
		private readonly string $groupId,
		private string          $name,
	)
	{
	}

	public function getGroupId(): string
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