<?php
declare(strict_types=1);

namespace App\User\Domain\Service;

use App\Common\Exception\DomainException;
use App\Common\Uuid\UuidProviderInterface;
use App\User\Domain\Model\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Service\Exception\ImageUploadException;
use App\User\Domain\Service\Exception\UserNotFoundException;
use App\User\Domain\Service\Input\CreateUserInput;
use App\User\Domain\Service\Input\UpdateUserInput;

readonly class UserService
{
	public function __construct(
		private UserRepositoryInterface $userRepository,
		private UuidProviderInterface   $uuidProvider,
		private ImageUploaderInterface  $imageUploader,
		private PasswordHasherInterface $passwordHasher,
	)
	{
	}

	/**
	 * @throws DomainException
	 */
	public function create(CreateUserInput $input): string
	{
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
			throw new UserNotFoundException();
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
}