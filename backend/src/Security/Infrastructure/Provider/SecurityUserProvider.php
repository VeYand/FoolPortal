<?php
declare(strict_types=1);

namespace App\Security\Infrastructure\Provider;

use App\Common\Exception\AppException;
use App\Security\App\Adapter\UserAdapterInterface;
use App\Security\Infrastructure\Model\SecurityUser;
use App\User\App\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException as UserNotFoundSecurityException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class SecurityUserProvider implements UserProviderInterface
{
	public function __construct(
		private readonly UserAdapterInterface $userAdapter,
	)
	{
	}

	/**
	 * @inheritDoc
	 * @throws AppException
	 */
	public function refreshUser(UserInterface $user): UserInterface
	{
		return $this->loadUserByIdentifier($user->getUserIdentifier());
	}

	/**
	 * @inheritDoc
	 */
	public function supportsClass(string $class): bool
	{
		return SecurityUser::class === $class || is_subclass_of($class, SecurityUser::class);
	}

	/**
	 * @inheritDoc
	 * @throws AppException
	 */
	public function loadUserByIdentifier(string $identifier): UserInterface
	{
		try
		{
			$user = $this->userAdapter->getUserByEmail($identifier);
		}
		catch (UserNotFoundException)
		{
			throw new UserNotFoundSecurityException();
		}

		return new SecurityUser(
			$user->userId,
			$user->role,
			$user->email,
			$user->password,
		);
	}
}