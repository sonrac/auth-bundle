<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Security\Token;

use Sonrac\OAuth2\Adapter\League\Entity\ClientEntityInterface;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class OAuthToken
 * @package Sonrac\OAuth2\Security\Token
 */
class OAuthToken extends AbstractOAuthToken
{
    /**
     * OAuthToken constructor.
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @param \Sonrac\OAuth2\Adapter\League\Entity\ClientEntityInterface $client
     * @param string $credentials
     * @param string $providerKey
     * @param array $scopes
     * @param array $roles
     */
    public function __construct(
        UserInterface $user,
        ClientEntityInterface $client,
        string $providerKey,
        ?string $credentials = null,
        array $scopes = [],
        array $roles = []
    ) {
        parent::__construct($client, $providerKey, $credentials, $scopes, $roles);

        $this->setUser($user);

        if (0 === \count($roles)) {
            AbstractToken::setAuthenticated(false);
        }
    }
}
