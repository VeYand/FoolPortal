<?php
declare(strict_types=1);

namespace App\Session\Infrastructure\Provider;

use App\Common\Uuid\UuidProviderInterface;
use App\Security\App\Adapter\Data\UserRole as SecurityUserRole;
use App\Security\Infrastructure\Model\SecurityUser;
use App\Session\App\Exception\AppException;
use App\Session\App\Provider\Data\SessionUser;
use App\Session\App\Provider\SessionProviderInterface;
use App\User\Domain\Model\UserRole;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

readonly class SessionProvider implements SessionProviderInterface
{
	public function __construct(
		private TokenStorageInterface $tokenStorage,
		private UuidProviderInterface $uuidProvider,
	)
	{
	}

	/**
	 * @throws AppException
	 */
	public function getCurrentUser(): SessionUser
	{
		$token = $this->tokenStorage->getToken();

		if (!$token)
		{
			throw new AppException('Not authorized', AppException::NOT_AUTHORIZED);
		}

		$user = $token->getUser();
		if (!$user instanceof SecurityUser)
		{
			throw new AppException('Security user must be an instance of SecurityUser');
		}

		return new SessionUser(
			$this->uuidProvider->fromBytesToUuid($user->getUserId()),
			$user->getUserIdentifier(),
			self::remapUserRole($user->getAppRole()),
		);
	}

	/**
	 * @throws AppException
	 */
	private static function remapUserRole(SecurityUserRole $role): UserRole
	{
		return match ($role)
		{
			SecurityUserRole::OWNER => UserRole::OWNER,
			SecurityUserRole::ADMIN => UserRole::ADMIN,
			SecurityUserRole::TEACHER => UserRole::TEACHER,
			SecurityUserRole::STUDENT => UserRole::STUDENT,
			default => throw new AppException('Unknown role')
		};
	}
}