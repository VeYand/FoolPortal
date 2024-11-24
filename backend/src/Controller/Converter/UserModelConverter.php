<?php
declare(strict_types=1);

namespace App\Controller\Converter;

use App\User\App\Query\Data\DetailedUserData;
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
			'role' => $user->role->name,
			'imageSrc' => $user->imageSrc,
			'email' => $user->email,
			'groupIds' => $user->groupIds,
		]);
	}
}