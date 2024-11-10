<?php
declare(strict_types=1);

namespace App\User\Infrastructure\Repository;

use App\User\Domain\Model\Group;
use App\User\Domain\Repository\GroupRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class GroupRepository implements GroupRepositoryInterface
{
	private EntityRepository $repository;

	public function __construct(
		private readonly EntityManagerInterface $entityManager,
	)
	{
		$this->repository = $this->entityManager->getRepository(Group::class);
	}

	public function find(string $groupId): ?Group
	{
		return $this->repository->find($groupId);
	}

	/**
	 * @inheritDoc
	 */
	public function findAll(): array
	{
		return $this->repository->findAll();
	}

	public function store(Group $group): string
	{
		$this->entityManager->persist($group);
		$this->entityManager->flush();
		return $group->getGroupId();
	}

	public function delete(Group $group): void
	{
		$this->entityManager->remove($group);
		$this->entityManager->flush();
	}
}