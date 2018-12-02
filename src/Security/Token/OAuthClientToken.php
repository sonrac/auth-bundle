<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Security\Token;

use Sonrac\OAuth2\Adapter\League\Entity\ClientEntityInterface;

/**
 * Class OAuthClientToken
 * @package Sonrac\OAuth2\Security\Token
 */
class OAuthClientToken extends AbstractOAuthToken
{
    /**
     * @var \Sonrac\OAuth2\Adapter\League\Entity\ClientEntityInterface
     */
    private $client;

    /**
     * OAuthClientToken constructor.
     * @param \Sonrac\OAuth2\Adapter\League\Entity\ClientEntityInterface $client
     * @param string $credentials
     * @param string $providerKey
     * @param array $scopes
     * @param array $roles
     */
    public function __construct(
        ClientEntityInterface $client,
        string $credentials,
        string $providerKey,
        array $scopes = [],
        array $roles = []
    ) {
        parent::__construct($credentials, $providerKey, $scopes, $roles);

        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function getClient(): ClientEntityInterface
    {
        return $this->client;
    }
}
