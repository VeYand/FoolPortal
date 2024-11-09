<?php
declare(strict_types=1);

namespace App\User\Domain\Service\Input;

use App\User\Domain\Model\UserRole;

readonly class UpdateUserInput
{
	public function __construct(
		public string    $userId,
		public ?string   $firstName,
		public ?string   $lastName,
		public ?string   $patronymic,
		public ?UserRole $role,
		public ?string   $base64ImageData,
		public ?string   $email,
		public ?string   $plainPassword,
	)
	{
	}
}