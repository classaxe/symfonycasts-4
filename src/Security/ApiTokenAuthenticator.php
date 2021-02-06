<?php

namespace App\Security;

use App\Repository\ApiTokenRepository;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ApiTokenAuthenticator extends AbstractGuardAuthenticator
{
    /** @var ApiTokenRepository */
    private $apiTokenRepository;

    public function __construct(ApiTokenRepository $apiTokenRepository)
    {
        $this->apiTokenRepository = $apiTokenRepository;
    }

    public function supports(Request $request)
    {
        return $request->headers->has('Authorization') &&
            0 === strpos($request->headers->get('Authorization'), 'Bearer ');
    }

    public function getCredentials(Request $request)
    {
        $authorizationHeader = $request->headers->get('Authorization');
        return substr($authorizationHeader, strlen('Bearer '));
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (!$token = $this->apiTokenRepository->findOneBy([
            'token' => $credentials
        ])) {
            throw new CustomUserMessageAuthenticationException('Invalid API Token');
        }
        if ($token->isExpired()) {
            throw new CustomUserMessageAuthenticationException('API Token has expired');
        }
        return $token->getUser();
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // Nothing to check
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            'message' => $exception->getMessageKey(),
        ], 401);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // allow the request to continue
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        throw new Exception('Not used: entry_point from other authenticator is used');
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
