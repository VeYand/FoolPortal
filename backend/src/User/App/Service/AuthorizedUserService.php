<?php
declare(strict_types=1);

namespace App\User\App\Service;

use App\Common\Exception\AppException;
use App\User\App\Adapter\SessionAdapterInterface;
use App\User\Domain\Service\Input\CreateUserInput;
use App\User\Domain\Service\Input\UpdateUserInput;

readonly class AuthorizedUserService implements UserServiceInterface
{
	public function __construct(
		private UserServiceInterface    $trustedUserService,
		private SessionAdapterInterface $sessionAdapter,
	)
	{
	}

	/**
	 * @throws AppException
	 */
	public function create(CreateUserInput $input): void
	{
		if (!$this->sessionAdapter->isLoggedAdmin())
		{
			throw new AppException('Access denied: only administrators can create users', 403);
		}

		$this->trustedUserService->create($input);
	}


	/**
	 * @throws AppException
	 */
	public function update(UpdateUserInput $input): void
	{
		if (!$this->sessionAdapter->isLoggedAdmin())
		{
			throw new AppException('Access denied: only administrators can update users', 403);
		}

		$this->trustedUserService->update($input);
	}

	/**
	 * @throws AppException
	 */
	public function delete(string $userId): void
	{
		if (!$this->sessionAdapter->isLoggedAdmin())
		{
			throw new AppException('Access denied: only administrators can delete users', 403);
		}

		$this->trustedUserService->delete($userId);
	}
}