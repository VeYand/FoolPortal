<?php
declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\Common\Uuid\UuidInterface;
use App\User\Domain\Model\User;

interface UserReadRepositoryInterface
{
	public function find(UuidInterface $userId): ?User;

	public function findByEmail(string $email): ?User;

	/**
	 * @return User[]
	 */
	public function findAll(): array;
}