<?php
declare(strict_types=1);

namespace App\Tests\Unit\User\Domain\Infrastructure;

use App\User\Domain\Model\User;
use App\User\Domain\Repository\UserRepositoryInterface;

class UserInMemoryRepository implements UserRepositoryInterface
{
	public function find(string $userId): ?User
	{
		// TODO: Implement find() method.
		return null;
	}

	public function findByEmail(string $email): ?User
	{
		// TODO: Implement findByEmail() method.
		return null;
	}

	/**
	 * @inheritDoc
	 */
	public function findAll(): array
	{
		// TODO: Implement findAll() method.
		return [];
	}

	public function store(User $user): string
	{
		// TODO: Implement store() method.
		return '';
	}

	public function delete(User $user): void
	{
		// TODO: Implement delete() method.
	}
}