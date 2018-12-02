<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Security;

use Sonrac\OAuth2\Adapter\League\Repository\ClientRepositoryInterface;
use Sonrac\OAuth2\Security\Token\AbstractOAuthToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class OAuthAuthenticationProvider
 * @package Sonrac\OAuth2\Security
 */
class OAuthAuthenticationProvider implements AuthenticationProviderInterface
{
    /**
     * @var \Sonrac\OAuth2\Adapter\League\Repository\ClientRepositoryInterface
     */
    private $clientRepository;

    /**
     * @var \Symfony\Component\Security\Core\User\UserProviderInterface
     */
    private $userProvider;

    /**
     * @var string
     */
    private $providerKey;

    /**
     * OAuthAuthenticationProvider constructor.
     * @param \Sonrac\OAuth2\Adapter\League\Repository\ClientRepositoryInterface $clientRepository
     * @param \Symfony\Component\Security\Core\User\UserProviderInterface $userProvider
     * @param string $providerKey
     */
    public function __construct(
        ClientRepositoryInterface $clientRepository,
        UserProviderInterface $userProvider,
        string $providerKey
    ) {
        $this->clientRepository = $clientRepository;
        $this->providerKey = $providerKey;
        $this->userProvider = $userProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate(TokenInterface $token)
    {
        return $token;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof AbstractOAuthToken && $token->getProviderKey() === $this->providerKey;
    }
}
