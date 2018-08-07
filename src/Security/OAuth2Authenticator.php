<?php

declare(strict_types=1);

namespace sonrac\Auth\Security;

use sonrac\Auth\Providers\ClientProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AuthenticatorInterface;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

/**
 * Class OAuth2Authenticator.
 */
class OAuth2Authenticator implements AuthenticatorInterface
{
    /**
     * Token header name.
     *
     * @var string
     */
    private $tokenName;

    /**
     * OAuth2Authenticator constructor.
     *
     * @param string $tokenName
     */
    public function __construct(string $tokenName)
    {
        $this->tokenName = $tokenName;
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request): bool
    {
        return $request->headers->has($this->tokenName);
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request)
    {
        return [
            'token' => $request->headers->get($this->tokenName),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = $credentials['token'];

        if (null === $token) {
            return;
        }

        return $userProvider->loadUserByUsername($token);
    }

    /**
     * {@inheritdoc}
     */
    public function getClient($credentials, ClientProviderInterface $clientProvider)
    {
        $token = $credentials['token'];

        if (null === $token) {
            return;
        }

        return $clientProvider->findByName($token);
    }

    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function createAuthenticatedToken(UserInterface $user, $providerKey)
    {
        return new PostAuthenticationGuardToken(
            $user,
            $providerKey,
            $user->getRoles()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return (new JsonResponse())->setContent(json_encode([
            'status' => false,
            'message' => 'Authentication failure'
        ]));
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsRememberMe(): bool
    {
        return false;
    }
}
