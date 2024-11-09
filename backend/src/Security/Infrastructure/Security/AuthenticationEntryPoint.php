<?php
declare(strict_types=1);

namespace App\Security\Infrastructure\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class AuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    private const string CONTENT_TYPE_TEXT_HTML = 'text/html';

    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
    )
    {
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        if ($request->getMethod() === Request::METHOD_GET &&
            in_array(self::CONTENT_TYPE_TEXT_HTML, $request->getAcceptableContentTypes()))
        {
            return new RedirectResponse($this->urlGenerator->generate('login'));
        }

        return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
    }
}