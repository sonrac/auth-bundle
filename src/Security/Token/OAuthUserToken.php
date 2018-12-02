<?php

declare(strict_types=1);

namespace sonrac\Auth\Security\Token;

use Sonrac\OAuth2\Adapter\League\Entity\ClientEntityInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class OAuthToken
 * @package sonrac\Auth\Security\Token
 */
class OAuthUserToken extends AbstractOAuthToken
{
    /**
     * @var \Sonrac\OAuth2\Adapter\League\Entity\ClientEntityInterface
     */
    private $client;

    /**
     * OAuthUserToken constructor.
     * @param \Sonrac\OAuth2\Adapter\League\Entity\ClientEntityInterface $client
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @param string $credentials
     * @param string $providerKey
     * @param array $scopes
     * @param array $roles
     */
    public function __construct(
        ClientEntityInterface $client,
        UserInterface $user,
        string $credentials,
        string $providerKey,
        array $scopes = [],
        array $roles = []
    ) {
        parent::__construct($credentials, $providerKey, $scopes, $roles);

        $this->client = $client;

        $this->setUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function getClient(): ClientEntityInterface
    {
        return $this->client;
    }
}
