<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Converter\UserModelConverter;
use App\Controller\Exception\ExceptionHandler;
use App\Session\Api\SessionApiInterface;
use App\User\Api\UserApiInterface;
use OpenAPI\Server\Api\AuthorizationApiInterface as AuthorizationApiHandlerInterface;
use OpenAPI\Server\Model\LoginInput;
use OpenAPI\Server\Model\EmptyResponse as ApiEmptyResponse;

readonly class AuthorizationApiHandler implements AuthorizationApiHandlerInterface
{
	public function __construct(
		private SessionApiInterface $sessionApi,
		private UserApiInterface    $userApi,
		private ExceptionHandler    $exceptionHandler,
	)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function getLoggedUser(int &$responseCode, array &$responseHeaders): array|object|null
	{
		return $this->exceptionHandler->executeWithHandle(function ()
		{
			$loggedUserId = $this->sessionApi->getCurrentUser()->id;
			$user = $this->userApi->getDetailedUserById($loggedUserId);

			return UserModelConverter::convertUserDataToApiUserData($user);
		}, $responseCode, $responseHeaders);
	}

	/**
	 * @inheritDoc
	 */
	public function login(LoginInput $loginInput, int &$responseCode, array &$responseHeaders): array|null|object
	{
		return new ApiEmptyResponse();
	}

	/**
	 * @inheritDoc
	 */
	public function logout(int &$responseCode, array &$responseHeaders): array|null|object
	{
		return new ApiEmptyResponse();
	}
}