<?php
declare(strict_types=1);

namespace App\User\App\Query\Data;

use App\User\Domain\Model\UserRole;

readonly class DetailedUserData
{
	/**
	 * @param string[] $groupIds
	 */
	public function __construct(
		public string   $userId,
		public string   $firstName,
		public string   $lastName,
		public ?string  $patronymic,
		public UserRole $role,
		public ?string  $imageSrc,
		public string   $email,
		public array    $groupIds,
	)
	{
	}
}