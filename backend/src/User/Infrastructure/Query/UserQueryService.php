<?php
declare(strict_types=1);

namespace App\User\Infrastructure\Query;

use App\Common\Uuid\UuidInterface;
use App\Common\Uuid\UuidUtils;
use App\User\App\Exception\AppException;
use App\User\App\Query\Data\DetailedUserData;
use App\User\App\Query\Data\UserData;
use App\User\App\Query\ImageQueryServiceInterface;
use App\User\App\Query\Spec\ListUsersSpec;
use App\User\App\Query\UserQueryServiceInterface;
use App\User\Domain\Model\User;
use App\User\Domain\Repository\GroupMemberReadRepositoryInterface;
use App\User\Domain\Repository\UserReadRepositoryInterface;
use App\User\Domain\Model\GroupMember;
use Doctrine\ORM\EntityManagerInterface;

readonly class UserQueryService implements UserQueryServiceInterface
{
	public function __construct(
		private UserReadRepositoryInterface        $userReadRepository,
		private GroupMemberReadRepositoryInterface $groupMemberReadRepository,
		private ImageQueryServiceInterface         $imageQueryService,
		private EntityManagerInterface             $entityManager,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function getUserById(UuidInterface $userId): UserData
	{
		$user = $this->userReadRepository->find($userId);

		if (is_null($user))
		{
			throw new AppException('User not found', AppException::USER_NOT_FOUND);
		}

		return $this->convertUserToUserData($user);
	}

	/**
	 * @inheritDoc
	 */
	public function getDetailedUserById(UuidInterface $userId): DetailedUserData
	{
		$user = $this->userReadRepository->find($userId);

		if (is_null($user))
		{
			throw new AppException('User not found', AppException::USER_NOT_FOUND);
		}

		$userIdToGroupIdsMap = $this->getUserIdToGroupIdsMap([$userId]);
		return $this->expandUserByGroups($user, $userIdToGroupIdsMap);
	}

	/**
	 * @inheritDoc
	 */
	public function getUserByEmail(string $email): UserData
	{
		$user = $this->userReadRepository->findByEmail($email);

		if (is_null($user))
		{
			throw new AppException('User not found', AppException::USER_NOT_FOUND);
		}

		return $this->convertUserToUserData($user);
	}

	/**
	 * @inheritDoc
	 */
	public function getUserHashedPassword(UuidInterface $userId): string
	{
		$user = $this->userReadRepository->find($userId);

		if (is_null($user))
		{
			throw new AppException('User not found', AppException::USER_NOT_FOUND);
		}

		return $user->getPassword();
	}

	/**
	 * @inheritDoc
	 */
	public function listUsers(ListUsersSpec $spec): array
	{
		$qb = $this->entityManager->createQueryBuilder();

		$qb->select('u')
			->from(User::class, 'u')
			->leftJoin(GroupMember::class, 'gm', 'WITH', 'u.userId = gm.userId');

		if (!empty($spec->groupIds))
		{
			$qb->andWhere('gm.groupId IN (:groupIds)')
				->setParameter('groupIds', UuidUtils::convertToBinaryList($spec->groupIds));
		}

		$users = $qb->getQuery()->getResult();

		$userIds = array_map(
			static fn(User $user) => $user->getUserId(),
			$users,
		);
		$userIdToGroupIdsMap = $this->getUserIdToGroupIdsMap($userIds);
		return array_map(
			function (User $user) use ($userIdToGroupIdsMap)
			{
				return $this->expandUserByGroups($user, $userIdToGroupIdsMap);
			},
			$users,
		);
	}

	/**
	 * @param UuidInterface[] $userIds
	 * @return array<string, UuidInterface[]>
	 */
	private function getUserIdToGroupIdsMap(array $userIds): array
	{
		$groupMembers = $this->groupMemberReadRepository->findByUsers($userIds);

		/** @var array<string, UuidInterface[]> $userIdToGroupIdsMap */
		$userIdToGroupIdsMap = [];
		foreach ($groupMembers as $groupMember)
		{
			$userId = $groupMember->getUserId();
			$groupId = $groupMember->getGroupId();
			$userIdToGroupIdsMap[$userId->toString()][] = $groupId;
		}

		return $userIdToGroupIdsMap;
	}

	/**
	 * @param array<string, UuidInterface[]> $userIdToGroupIdsMap
	 */
	private function expandUserByGroups(User $user, array $userIdToGroupIdsMap): DetailedUserData
	{
		$groupIds = $userIdToGroupIdsMap[$user->getUserId()->toString()] ?? [];
		return $this->convertUserToDetailedUserData($user, $groupIds);
	}

	private function convertUserToUserData(User $user): UserData
	{
		return new UserData(
			$user->getUserId(),
			$user->getFirstName(),
			$user->getLastName(),
			$user->getPatronymic(),
			$user->getRole(),
			$this->imageQueryService->getImageUrl($user->getImagePath()),
			$user->getEmail(),
		);
	}

	/**
	 * @param UuidInterface[] $groupIds
	 */
	private function convertUserToDetailedUserData(User $user, array $groupIds): DetailedUserData
	{
		$imageSrc = $this->imageQueryService->getImageUrl($user->getImagePath());

		return new DetailedUserData(
			$user->getUserId(),
			$user->getFirstName(),
			$user->getLastName(),
			$user->getPatronymic(),
			$user->getRole(),
			$imageSrc,
			$user->getEmail(),
			$groupIds,
		);
	}
}