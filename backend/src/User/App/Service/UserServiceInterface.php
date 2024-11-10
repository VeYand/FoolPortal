<?php
declare(strict_types=1);

namespace App\User\App\Service;

use App\User\Domain\Service\Input\CreateUserInput;
use App\User\Domain\Service\Input\UpdateUserInput;

interface UserServiceInterface
{
	public function create(CreateUserInput $input): void;

	public function update(UpdateUserInput $input): void;

	public function delete(string $userId): void;
}