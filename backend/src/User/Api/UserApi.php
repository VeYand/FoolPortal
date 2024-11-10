<?php
declare(strict_types=1);

namespace App\User\Api;

use App\User\App\Query\Data\UserData;
use App\User\App\Query\UserQueryServiceInterface;

readonly class UserApi implements UserApiInterface
{
	public function __construct(
		private UserQueryServiceInterface $userQueryService,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function getUserByEmail(string $email): UserData
	{
		return $this->userQueryService->getUserByEmail($email);
	}

	/**
	 * @inheritDoc
	 */
	public function getUserHashedPassword(string $userId): string
	{
		return $this->userQueryService->getUserHashedPassword($userId);
	}
}