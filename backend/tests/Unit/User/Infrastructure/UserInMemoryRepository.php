<?php
declare(strict_types=1);

namespace App\Tests\Unit\User\Infrastructure;

use App\User\Domain\Model\User;
use App\User\Domain\Repository\UserRepositoryInterface;

class UserInMemoryRepository implements UserRepositoryInterface
{
	/** @var array<string, User> */
	private array $users = [];

	public function find(string $userId): ?User
	{
		return $this->users[$userId] ?? null;
	}

	public function findByEmail(string $email): ?User
	{
		foreach ($this->users as $userId => $user)
		{
			if ($user->getEmail() === $email)
			{
				return $user;
			}
		}

		return null;
	}

	/**
	 * @inheritDoc
	 */
	public function findAll(): array
	{
		return array_values($this->users);
	}

	public function store(User $user): string
	{
		$this->users[$user->getUserId()] = $user;
		return $user->getUserId();
	}

	public function delete(User $user): void
	{
		unset($this->users[$user->getUserId()]);
	}
}