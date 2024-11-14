<?php
declare(strict_types=1);

namespace App\Security\Infrastructure\Adapter;

use App\Security\App\Adapter\Data\UserData;
use App\Security\App\Adapter\UserAdapterInterface;
use App\Security\App\Exception\AppException;
use App\User\Api\Exception\ApiException as UserApiException;
use App\User\Api\UserApiInterface;
use App\Security\App\Adapter\Data\UserRole;
use App\User\Domain\Model\UserRole as AdaptedUserRole;

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
	public function getUserByEmail(string $email): UserData
	{
		return self::tryExecute(function () use ($email)
		{
			$user = $this->userApi->getUserByEmail($email);
			$password = $this->userApi->getUserHashedPassword($user->userId);

			return new UserData(
				$user->userId,
				self::remapUserRole($user->role),
				$user->email,
				$password,
			);
		});
	}

	/**
	 * @throws AppException
	 */
	private static function remapUserRole(AdaptedUserRole $role): UserRole
	{
		return match ($role)
		{
			AdaptedUserRole::OWNER => UserRole::OWNER,
			AdaptedUserRole::ADMIN => UserRole::ADMIN,
			AdaptedUserRole::TEACHER => UserRole::TEACHER,
			AdaptedUserRole::STUDENT => UserRole::STUDENT,
			default => throw new AppException('Unknown role')
		};
	}

	/**
	 * @throws AppException
	 */
	private static function tryExecute(callable $callable): mixed
	{
		try
		{
			return $callable();
		}
		catch (UserApiException $e)
		{
			throw new AppException(
				$e->getMessage(),
				self::remapExceptionCode($e->getCode()),
				$e,
			);
		}
	}

	private static function remapExceptionCode(int $code): int
	{
		return match ($code)
		{
			UserApiException::USER_NOT_FOUND => AppException::USER_NOT_FOUND,
			default => AppException::INTERNAL,
		};
	}
}