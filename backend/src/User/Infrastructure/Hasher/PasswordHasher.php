<?php
declare(strict_types=1);

namespace App\User\Infrastructure\Hasher;

use App\User\Domain\Service\PasswordHasherInterface as DomainPasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\CheckPasswordLengthTrait;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class PasswordHasher implements DomainPasswordHasherInterface, PasswordHasherInterface
{
	private const string SALT = '_salt';

	use CheckPasswordLengthTrait;

	public function hash(string $plainPassword): string
	{
		if ($this->isPasswordTooLong($plainPassword))
		{
			throw DomainException();
		}

		return $this->encodePassword($plainPassword);
	}

	public function verify(string $hashedPassword, #[\SensitiveParameter] string $plainPassword): bool
	{
		if ($plainPassword === '' || $this->isPasswordTooLong($plainPassword))
		{
			return false;
		}

		return $this->encodePassword($plainPassword) === $hashedPassword;
	}

	public function needsRehash(string $hashedPassword): bool
	{
		return false;
	}

	private function encodePassword(string $plainPassword): string
	{
		return md5($plainPassword . self::SALT);
	}
}