<?php
declare(strict_types=1);

namespace App\Session\Api;

use App\Session\Api\Exception\ApiException;
use App\Security\App\Exception\AppException;
use App\Session\App\Provider\Data\SessionUser;
use App\Session\App\Provider\SessionProviderInterface;

readonly class SessionApi implements SessionApiInterface
{
	public function __construct(
		private SessionProviderInterface $sessionProvider,
	)
	{
	}

	/**
	 * @throws ApiException
	 */
	public function getCurrentUser(): SessionUser
	{
		return self::tryExecute(function ()
		{
			return $this->sessionProvider->getCurrentUser();
		});
	}

	/**
	 * @throws ApiException
	 */
	public static function tryExecute(callable $callback): mixed
	{
		try
		{
			return $callback();
		}
		catch (AppException $e)
		{
			throw new ApiException($e->getMessage(), $e->getCode(), $e);
		}
	}
}