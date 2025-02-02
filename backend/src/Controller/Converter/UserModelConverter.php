<?php
declare(strict_types=1);

namespace App\Controller\Converter;

use App\Common\Uuid\UuidInterface;
use App\Common\Uuid\UuidProviderInterface;
use App\User\Api\Exception\ApiException as UserApiException;
use App\User\App\Query\Data\DetailedUserData;
use App\User\Domain\Model\UserRole;
use App\User\Domain\Service\Input\CreateUserInput;
use App\User\Domain\Service\Input\UpdateUserInput;
use OpenAPI\Server\Model\CreateUserRequest;
use OpenAPI\Server\Model\UpdateUserRequest;
use OpenAPI\Server\Model\UserData as ApiUserData;

readonly class UserModelConverter
{
	public static function convertUserDataToApiUserData(DetailedUserData $user): ApiUserData
	{
		return new ApiUserData([
			'userId' => $user->userId->toString(),
			'firstName' => $user->firstName,
			'lastName' => $user->lastName,
			'patronymic' => $user->patronymic,
			'role' => $user->role->value,
			'imageSrc' => $user->imageSrc,
			'email' => $user->email,
			'groupIds' => array_map(static fn(UuidInterface $groupId) => $groupId->toString(), $user->groupIds),
		]);
	}

	/**
	 * @param DetailedUserData[] $users
	 * @return ApiUserData[]
	 */
	public static function convertUsersToApiUsers(array $users): array
	{
		return array_map(
			static fn(DetailedUserData $user) => self::convertUserDataToApiUserData($user),
			$users,
		);
	}

	/**
	 * @throws UserApiException
	 */
	public static function convertApiRoleToUserRole(int $role): UserRole
	{
		return UserRole::tryFrom($role) ?? throw new UserApiException('Invalid role', UserApiException::INVALID_USER_ROLE);
	}

	/**
	 * @param int[]|null $roles
	 * @return UserRole[]|null
	 * @throws UserApiException
	 */
	public static function convertApiRolesToUserRoles(?array $roles): ?array
	{
		if (is_null($roles))
		{
			return null;
		}

		return array_map(static fn(int $role) => self::convertApiRoleToUserRole($role), $roles);
	}

	/**
	 * @throws UserApiException
	 */
	public static function convertCreateUserRequestToCreateUserInput(CreateUserRequest $request): CreateUserInput
	{
		return new CreateUserInput(
			$request->getFirstName(),
			$request->getLastName(),
			$request->getPatronymic(),
			self::convertApiRoleToUserRole($request->getRole()),
			$request->getImageData(),
			$request->getEmail(),
			$request->getPassword(),
		);
	}

	/**
	 * @throws UserApiException
	 */
	public static function convertUpdateUserRequestToUpdateUserInput(UpdateUserRequest $request, UuidProviderInterface $uuidProvider): UpdateUserInput
	{
		return new UpdateUserInput(
			$uuidProvider->fromStringToUuid($request->getUserId()),
			$request->getFirstName(),
			$request->getLastName(),
			$request->getPatronymic(),
			is_null($request->getRole())
				? null
				: self::convertApiRoleToUserRole($request->getRole()),
			$request->getImageData(),
			$request->getEmail(),
			$request->getPassword(),
		);
	}
}