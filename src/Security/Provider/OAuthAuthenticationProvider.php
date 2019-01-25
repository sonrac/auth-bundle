<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Security\Provider;

use Sonrac\OAuth2\Security\Token\AbstractPreAuthenticationToken;
use Sonrac\OAuth2\Security\Token\OAuthClientToken;
use Sonrac\OAuth2\Security\Token\OAuthToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class OAuthAuthenticationProvider
 * @package Sonrac\OAuth2\Security\Provider
 */
class OAuthAuthenticationProvider implements AuthenticationProviderInterface
{
    /**
     * @var \Symfony\Component\Security\Core\User\UserProviderInterface
     */
    private $userProvider;

    /**
     * @var \Symfony\Component\Security\Core\User\UserCheckerInterface
     */
    private $userChecker;

    /**
     * @var string
     */
    private $providerKey;

    /**
     * OAuthAuthenticationProvider constructor.
     * @param \Symfony\Component\Security\Core\User\UserProviderInterface $userProvider
     * @param \Symfony\Component\Security\Core\User\UserCheckerInterface $userChecker
     * @param string $providerKey
     */
    public function __construct(
        UserProviderInterface $userProvider,
        UserCheckerInterface $userChecker,
        string $providerKey
    ) {
        $this->userProvider = $userProvider;
        $this->userChecker = $userChecker;
        $this->providerKey = $providerKey;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface|\Sonrac\OAuth2\Security\Token\PreAuthenticationToken $token
     */
    public function authenticate(TokenInterface $token)
    {
        if (false === $this->supports($token)) {
            throw new AuthenticationException('The token is not supported by this authentication provider.');
        }

        try {
            if (null !== $token->getUser() && '' !== $token->getUser()) {
                $user = $this->userProvider->loadUserByUsername($token->getUser());
            } else {
                $user = null;
            }
        } catch (UsernameNotFoundException $exception) {
            throw new BadCredentialsException('Bad user credentials.', 0, $exception);
        }

        if (false === $user instanceof UserInterface) {
            return new OAuthClientToken($token->getClient(), $token->getProviderKey(), $token->getCredentials(), $token->getScopes());
        }

        $this->userChecker->checkPreAuth($user);
        $this->userChecker->checkPostAuth($user);

        return new OAuthToken(
            $user, $token->getClient(), $token->getProviderKey(), $token->getCredentials(), $token->getScopes(), $user->getRoles()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof AbstractPreAuthenticationToken && $token->getProviderKey() === $this->providerKey;
    }
}
