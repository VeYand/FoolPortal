<?php
declare(strict_types=1);

namespace App\Subject\Infrastructure\Adapter;

use App\Subject\App\Adapter\Data\UserData;
use App\Subject\App\Adapter\Data\UserRole;
use App\Subject\App\Adapter\UserAdapterInterface;
use App\Subject\App\Exception\AppException;
use App\User\Api\Exception\ApiException as UserApiException;
use App\User\Api\UserApiInterface;
use App\User\Domain\Model\UserRole as UserUserRole;

readonly class UserAdapter implements UserAdapterInterface
{
	public function __construct(
		private UserApiInterface $userApi,
	)
	{
	}

	/**
	 * @throws AppException
	 */
	public function getUser(string $userId): ?UserData
	{
		try
		{
			$user = $this->userApi->getUserById($userId);
		}
		catch (UserApiException $e)
		{
			if ($e->getCode() === UserApiException::USER_NOT_FOUND)
			{
				return null;
			}
			throw new AppException($e->getMessage(), AppException::INTERNAL, $e);
		}

		return new UserData($user->userId, self::remapUserRole($user->role));
	}

	public function isGroupExists(string $groupId): bool
	{
		return $this->userApi->isGroupExists($groupId);
	}

	/**
	 * @throws AppException
	 */
	private static function remapUserRole(UserUserRole $role): UserRole
	{
		return match ($role)
		{
			UserUserRole::OWNER => UserRole::OWNER,
			UserUserRole::ADMIN => UserRole::ADMIN,
			UserUserRole::TEACHER => UserRole::TEACHER,
			UserUserRole::STUDENT => UserRole::STUDENT,
			default => throw new AppException('Unknown role')
		};
	}
}