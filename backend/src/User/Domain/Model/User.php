<?php
declare(strict_types=1);

namespace App\User\Domain\Model;

class User
{
	private int $role;

	public function __construct(
		private readonly string $userId,
		private string          $firstName,
		private string          $lastName,
		private ?string         $patronymic,
		UserRole                $role,
		private ?string         $imagePath,
		private string          $email,
		private string          $password,
	)
	{
		$this->role = $role->value;
	}

	public function getUserId(): string
	{
		return $this->userId;
	}

	public function getFirstName(): string
	{
		return $this->firstName;
	}

	public function setFirstName(string $firstName): void
	{
		$this->firstName = $firstName;
	}

	public function getLastName(): string
	{
		return $this->lastName;
	}

	public function setLastName(string $lastName): void
	{
		$this->lastName = $lastName;
	}

	public function getPatronymic(): ?string
	{
		return $this->patronymic;
	}

	public function setPatronymic(?string $patronymic): void
	{
		$this->patronymic = $patronymic;
	}

	public function getRole(): UserRole
	{
		return UserRole::from($this->role);
	}

	public function setRole(UserRole $role): void
	{
		$this->role = $role->value;
	}

	public function getImagePath(): ?string
	{
		return $this->imagePath;
	}

	public function setImagePath(?string $imagePath): void
	{
		$this->imagePath = $imagePath;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function setEmail(string $email): void
	{
		$this->email = $email;
	}

	public function getPassword(): string
	{
		return $this->password;
	}

	public function setPassword(string $password): void
	{
		$this->password = $password;
	}
}