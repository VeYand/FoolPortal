<?php
declare(strict_types=1);

namespace App\Controller\Converter;

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
			'userId' => $user->userId,
			'firstName' => $user->firstName,
			'lastName' => $user->lastName,
			'patronymic' => $user->patronymic,
			'role' => $user->role->value,
			'imageSrc' => $user->imageSrc,
			'email' => $user->email,
			'groupIds' => $user->groupIds,
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
	public static function convertUpdateUserRequestToUpdateUserInput(UpdateUserRequest $request): UpdateUserInput
	{
		return new UpdateUserInput(
			$request->getUserId(),
			$request->getFirstName(),
			$request->getLastName(),
			$request->getPatronymic(),
			is_null($request->getRole())
				? null
				: self::convertApiRoleToUserRole($request->getRole() ?? 0),
			$request->getImageData(),
			$request->getEmail(),
			$request->getPassword(),
		);
	}
}