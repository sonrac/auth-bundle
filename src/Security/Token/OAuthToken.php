<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Security\Token;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class OAuthToken.
 */
class OAuthToken extends AbstractOAuthToken
{
    /**
     * OAuthToken constructor.
     *
     * @param \Symfony\Component\Security\Core\User\UserInterface  $user
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface $client
     * @param string                                               $providerKey
     * @param string                                               $credentials
     * @param array                                                $scopes
     * @param array                                                $roles
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
