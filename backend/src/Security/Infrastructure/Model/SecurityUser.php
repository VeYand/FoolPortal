<?php
declare(strict_types=1);

namespace App\Security\Infrastructure\Model;

use App\Security\App\Adapter\Data\UserRole;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class SecurityUser implements UserInterface, PasswordAuthenticatedUserInterface
{
	public function __construct(
		private string   $userId,
		private UserRole $role,
		private string   $email,
		private string   $password,
	)
	{
	}

	public function getUserId(): string
	{
		return $this->userId;
	}

	/**
	 * @inheritDoc
	 */
	public function getPassword(): ?string
	{
		return $this->password;
	}

	/**
	 * @inheritDoc
	 */
	public function getRoles(): array
	{
		return ['ROLE_USER', $this->role->value];
	}

	public function getAppRole(): UserRole
	{
		return $this->role;
	}

	/**
	 * @inheritDoc
	 */
	public function eraseCredentials(): void
	{
	}

	/**
	 * @inheritDoc
	 */
	public function getUserIdentifier(): string
	{
		return $this->email;
	}
}