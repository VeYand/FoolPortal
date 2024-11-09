<?php
declare(strict_types=1);

namespace App\User\Infrastructure\Query;

use App\Common\Exception\AppException;
use App\User\App\Exception\UserNotFoundException;
use App\User\App\Query\Data\UserData;
use App\User\App\Query\ImageQueryServiceInterface;
use App\User\App\Query\UserQueryServiceInterface;
use App\User\Domain\Model\User;
use App\User\Domain\Repository\UserReadRepositoryInterface;

readonly class UserQueryService implements UserQueryServiceInterface
{
	public function __construct(
		private UserReadRepositoryInterface $userReadRepository,
		private ImageQueryServiceInterface  $imageQueryService,
	)
	{
	}

	/**
	 * @throws AppException
	 */
	public function getUserByEmail(string $email): UserData
	{
		$user = $this->userReadRepository->findByEmail($email);

		if (is_null($user))
		{
			throw new UserNotFoundException();
		}

		return $this->convertUserToUserData($user);
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
			$user->getPassword(),
		);
	}
}