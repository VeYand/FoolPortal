<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Converter\UserModelConverter;
use App\Controller\Exception\ExceptionHandler;
use App\User\Api\UserApiInterface;
use App\User\App\Query\Spec\ListUsersSpec;
use OpenAPI\Server\Api\UserApiInterface as UserApiHandlerInterface;
use OpenAPI\Server\Model\ListUsersSpec as ApiListUsersSpec;
use OpenAPI\Server\Model\UsersList as ApiUsersList;

readonly class UserApiHandler implements UserApiHandlerInterface
{
	public function __construct(
		private UserApiInterface $userApi,
		private ExceptionHandler $exceptionHandler,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function listUsers(ApiListUsersSpec $listUsersSpec, int &$responseCode, array &$responseHeaders): array|object|null
	{
		return $this->exceptionHandler->executeWithHandle(function () use ($listUsersSpec)
		{
			$users = $this->userApi->listUsers(new ListUsersSpec($listUsersSpec->getGroupIds()));

			return new ApiUsersList([
				'users' => UserModelConverter::convertUsersToApiUsers($users),
			]);
		}, $responseCode, $responseHeaders);
	}
}