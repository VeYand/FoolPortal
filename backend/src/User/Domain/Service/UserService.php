<?php
declare(strict_types=1);

namespace App\User\Domain\Service;

use App\Common\Uuid\UuidProviderInterface;
use App\User\Domain\Exception\DomainException;
use App\User\Domain\Model\User;
use App\User\Domain\Repository\GroupMemberRepositoryInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Service\Input\CreateUserInput;
use App\User\Domain\Service\Input\UpdateUserInput;

readonly class UserService
{
	public function __construct(
		private UserRepositoryInterface        $userRepository,
		private GroupMemberRepositoryInterface $groupMemberRepository,
		private UuidProviderInterface          $uuidProvider,
		private ImageUploaderInterface         $imageUploader,
		private PasswordHasherInterface        $passwordHasher,
	)
	{
	}

	/**
	 * @throws DomainException
	 */
	public function create(CreateUserInput $input): string
	{
		$this->assertEmailIsUnique($input->email);
		$imagePath = $this->imageUploader->uploadImage($input->base64ImageData);
		$hashedPassword = $this->passwordHasher->hash($input->plainPassword);

		$user = new User(
			$this->uuidProvider->generate(),
			$input->firstName,
			$input->lastName,
			$input->patronymic,
			$input->role,
			$imagePath,
			$input->email,
			$hashedPassword,
		);

		return $this->userRepository->store($user);
	}

	/**
	 * @throws DomainException
	 */
	public function update(UpdateUserInput $input): void
	{
		$user = $this->userRepository->find($input->userId);

		if (is_null($user))
		{
			throw new DomainException('User not found', DomainException::USER_NOT_FOUND);
		}

		if (!is_null($input->firstName))
		{
			$user->setFirstName($input->firstName);
		}

		if (!is_null($input->lastName))
		{
			$user->setLastName($input->lastName);
		}

		if (!is_null($input->patronymic))
		{
			$user->setPatronymic($input->patronymic);
		}

		if (!is_null($input->role))
		{
			$user->setRole($input->role);
		}

		if (!is_null($input->email))
		{
			$this->assertEmailIsUnique($input->email);
			$user->setEmail($input->email);
		}

		if (!is_null($input->plainPassword))
		{
			$hashedPassword = $this->passwordHasher->hash($input->plainPassword);
			$user->setPassword($hashedPassword);
		}

		if (!is_null($input->base64ImageData))
		{
			$this->imageUploader->uploadImage($user->getImagePath());
			$imagePath = $this->imageUploader->uploadImage($input->base64ImageData);
			$user->setImagePath($imagePath);
		}

		$this->userRepository->store($user);
	}

	/**
	 * @throws DomainException
	 */
	public function delete(string $userId): void
	{
		$user = $this->userRepository->find($userId);

		if (!is_null($user))
		{
			$this->imageUploader->removeImage($user->getImagePath());
			$groupMembers = $this->groupMemberRepository->findByUser($user->getUserId());
			$this->groupMemberRepository->delete($groupMembers);
			$this->userRepository->delete($user);
		}
	}


	/**
	 * @throws DomainException
	 */
	private function assertEmailIsUnique(string $email): void
	{
		$user = $this->userRepository->findByEmail($email);

		if (!is_null($user))
		{
			throw new DomainException('User with email "' . $email . '" already exists', DomainException::EMAIL_IS_NOT_UNIQUE);
		}
	}
}