<?php
declare(strict_types=1);

namespace App\Tests\Unit\User\Domain\Infrastructure;

use App\Common\Uuid\UuidInterface;
use App\User\Domain\Model\User;
use App\User\Domain\Repository\UserRepositoryInterface;

class UserInMemoryRepository implements UserRepositoryInterface
{
	/** @var array<string, User>  */
	private array $users = [];

	public function find(UuidInterface $userId): ?User
	{
		return $this->users[$userId->toString()] ?? null;
	}

	public function findByEmail(string $email): ?User
	{
		foreach ($this->users as $user) {
			if ($user->getEmail() === $email) {
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

	public function store(User $user): UuidInterface
	{
		$this->users[$user->getUserId()->toString()] = $user;
		return $user->getUserId();
	}

	public function delete(User $user): void
	{
		$userId = $user->getUserId()->toString();
		unset($this->users[$userId]);
	}
}