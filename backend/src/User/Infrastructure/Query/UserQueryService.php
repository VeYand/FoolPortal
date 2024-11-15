<?php
declare(strict_types=1);

namespace App\User\Infrastructure\Query;

use App\User\App\Exception\AppException;
use App\User\App\Query\Data\DetailedUserData;
use App\User\App\Query\Data\UserData;
use App\User\App\Query\ImageQueryServiceInterface;
use App\User\App\Query\UserQueryServiceInterface;
use App\User\Domain\Model\User;
use App\User\Domain\Repository\GroupMemberReadRepositoryInterface;
use App\User\Domain\Repository\UserReadRepositoryInterface;

readonly class UserQueryService implements UserQueryServiceInterface
{
	public function __construct(
		private UserReadRepositoryInterface        $userReadRepository,
		private GroupMemberReadRepositoryInterface $groupMemberReadRepository,
		private ImageQueryServiceInterface         $imageQueryService,
	)
	{
	}

	/**
	 * @throws AppException
	 */
	public function getUserById(string $userId): UserData
	{
		$user = $this->userReadRepository->find($userId);

		if (is_null($user))
		{
			throw new AppException('User not found', AppException::USER_NOT_FOUND);
		}

		return $this->convertUserToUserData($user);
	}

	/**
	 * @throws AppException
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
	 * @throws AppException
	 */
	public function getUserHashedPassword(string $userId): string
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
	public function listAllUsers(): array
	{
		$users = $this->userReadRepository->findAll();
		$groupMembers = $this->groupMemberReadRepository->findAll();

		/** @var DetailedUserData[] $detailedUserDataList */
		$detailedUserDataList = [];

		$userGroupIds = [];
		foreach ($groupMembers as $groupMember)
		{
			$userId = $groupMember->getUserId();
			$groupId = $groupMember->getGroupId();

			if (!isset($userGroupIds[$userId]))
			{
				$userGroupIds[$userId] = [];
			}
			$userGroupIds[$userId][] = $groupId;
		}

		foreach ($users as $user)
		{
			$groupIds = $userGroupIds[$user->getUserId()] ?? [];

			$imageSrc = $this->imageQueryService->getImageUrl($user->getImagePath());

			$detailedUserDataList[] = new DetailedUserData(
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

		return $detailedUserDataList;
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
}