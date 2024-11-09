<?php
declare(strict_types=1);

namespace App\User\Infrastructure\Repository;

use App\User\Domain\Model\GroupMember;
use App\User\Domain\Repository\GroupMemberRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class GroupMemberRepository implements GroupMemberRepositoryInterface
{
	private EntityRepository $repository;

	public function __construct(
		private readonly EntityManagerInterface $entityManager,
	)
	{
		$this->repository = $this->entityManager->getRepository(GroupMember::class);
	}

	public function find(string $groupId, string $userId): ?GroupMember
	{
		return $this->repository->findOneBy([
			'groupId' => $groupId,
			'userId' => $userId,
		]);
	}

	public function store(GroupMember $groupMember): string
	{
		$this->entityManager->persist($groupMember);
		$this->entityManager->flush();
		return $groupMember->getGroupId();
	}

	public function delete(GroupMember $groupMember): void
	{
		$this->entityManager->remove($groupMember);
		$this->entityManager->flush();
	}
}