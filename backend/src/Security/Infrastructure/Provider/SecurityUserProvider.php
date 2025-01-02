<?php
declare(strict_types=1);

namespace App\Security\Infrastructure\Provider;

use App\Security\App\Adapter\UserAdapterInterface;
use App\Security\App\Exception\AppException;
use App\Security\Infrastructure\Model\SecurityUser;
use Symfony\Component\Security\Core\Exception\UserNotFoundException as UserNotFoundSecurityException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

readonly class SecurityUserProvider implements UserProviderInterface
{
	public function __construct(
		private UserAdapterInterface $userAdapter,
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
		catch (AppException $e)
		{
			if ($e->getCode() === AppException::USER_NOT_FOUND)
			{
				throw new UserNotFoundSecurityException();
			}
			throw $e;
		}

		return new SecurityUser(
			$user->userId->toBytes(),
			$user->role,
			$user->email,
			$user->password,
		);
	}
}