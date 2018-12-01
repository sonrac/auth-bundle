<?php

declare(strict_types=1);

namespace sonrac\Auth\Security;

use sonrac\Auth\Repository\ClientRepositoryInterface;
use sonrac\Auth\Repository\UserRepositoryInterface;
use sonrac\Auth\Security\Token\OAuthUserToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class OAuthAuthenticationProvider
 * @package sonrac\Auth\Security
 */
class OAuthAuthenticationProvider implements AuthenticationProviderInterface
{
    /**
     * @var \sonrac\Auth\Repository\UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var \sonrac\Auth\Repository\ClientRepositoryInterface
     */
    private $clientRepository;

    /**
     * @var string
     */
    private $providerKey;

    //TODO: replace with user provider, and maybe create client provider
    public function __construct(
        ClientRepositoryInterface $clientRepository,
        UserRepositoryInterface $userRepository,
        string $providerKey
    ) {
        $this->clientRepository = $clientRepository;
        $this->userRepository = $userRepository;
        $this->providerKey = $providerKey;
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate(TokenInterface $token)
    {
        if (false === $this->supports($token)) {
            throw new AuthenticationException('The token is not supported by this authentication provider.');
        }


    }

    /**
     * {@inheritdoc}
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof OAuthUserToken && $token->getProviderKey() === $this->providerKey;
    }
}
