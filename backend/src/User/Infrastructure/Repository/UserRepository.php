<?php
declare(strict_types=1);

namespace App\User\Infrastructure\Repository;

use App\User\Domain\Model\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class UserRepository implements UserRepositoryInterface
{
	private EntityRepository $repository;

	public function __construct(
		private readonly EntityManagerInterface $entityManager,
	)
	{
		$this->repository = $this->entityManager->getRepository(User::class);
	}

	public function find(string $userId): ?User
	{
		return $this->repository->find($userId);
	}

	public function store(User $user): string
	{
		$this->entityManager->persist($user);
		$this->entityManager->flush();
		return $user->getUserId();
	}

	public function delete(User $user): void
	{
		$this->entityManager->remove($user);
		$this->entityManager->flush();
	}
}