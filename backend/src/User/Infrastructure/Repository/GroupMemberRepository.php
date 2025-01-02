<?php
declare(strict_types=1);

namespace App\User\Infrastructure\Repository;

use App\Common\Uuid\UuidInterface;
use App\Common\Uuid\UuidUtils;
use App\User\Domain\Model\GroupMember;
use App\User\Domain\Repository\GroupMemberRepositoryInterface;
use Doctrine\DBAL\ArrayParameterType;
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

	public function find(UuidInterface $groupId, UuidInterface $userId): ?GroupMember
	{
		return $this->repository->findOneBy([
			'groupId' => $groupId,
			'userId' => $userId,
		]);
	}

	public function store(GroupMember $groupMember): UuidInterface
	{
		$this->entityManager->persist($groupMember);
		$this->entityManager->flush();
		return $groupMember->getGroupId();
	}

	/**
	 * @inheritDoc
	 */
	public function delete(array $groupMembers): void
	{
		foreach ($groupMembers as $groupMember)
		{
			$this->entityManager->remove($groupMember);
		}
		$this->entityManager->flush();
	}

	/**
	 * @inheritDoc
	 */
	public function findByGroup(UuidInterface $groupId): array
	{
		return $this->repository->findBy([
			'groupId' => $groupId,
		]);
	}

	/**
	 * @inheritDoc
	 */
	public function findByUsers(array $userIds): array
	{
		$qb = $this->repository->createQueryBuilder('gm');
		return $qb
			->where($qb->expr()->in('gm.userId', ':userIds'))
			->setParameter('userIds', UuidUtils::convertToBinaryList($userIds), ArrayParameterType::STRING)
			->getQuery()
			->getResult();
	}

	/**
	 * @inheritDoc
	 */
	public function findAll(): array
	{
		return $this->repository->findAll();
	}
}