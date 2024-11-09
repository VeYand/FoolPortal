<?php
declare(strict_types=1);

namespace App\Security\Infrastructure\Security;

use InvalidArgumentException;
use JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class UserLoginAuthenticator extends AbstractAuthenticator
{
	use TargetPathTrait;

	public const string LOGIN_ROUTE = '/login';

	public function authenticate(Request $request): Passport
	{
		try
		{
			$jsonData = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
		}
		catch (JsonException)
		{
			throw new AuthenticationException('Invalid authentication data');
		}

		try
		{
			$password = $this->getStringArrayValue('password', $jsonData);
			$email = $this->getStringArrayValue('email', $jsonData);
			$csrfToken = $this->getStringArrayValue('_csrf_token', $jsonData);
		}
		catch (InvalidArgumentException)
		{
			throw new AuthenticationException('Invalid authentication data');
		}

		$email = trim($email);

		return new Passport(
			new UserBadge($email),
			new PasswordCredentials($password),
			[
				new CsrfTokenBadge('authenticate', $csrfToken),
			],
		);
	}

	public function supports(Request $request): ?bool
	{
		return $request->isMethod('POST') && $request->getPathInfo() === self::LOGIN_ROUTE;
	}

	public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
	{
		return new Response('Successful authorization');
	}

	public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
	{
		return new Response($exception->getMessage(), Response::HTTP_UNAUTHORIZED);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	private function getStringArrayValue(string $key, array $jsonData): string
	{
		$content = $jsonData[$key] ?? null;

		if (!is_string($content))
		{
			throw new InvalidArgumentException($key);
		}

		return $content;
	}
}