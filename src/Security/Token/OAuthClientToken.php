<?php

declare(strict_types=1);

namespace sonrac\Auth\Security\Token;

use sonrac\Auth\Entity\ClientInterface;

/**
 * Class OAuthClientToken
 * @package sonrac\Auth\Security\Token
 */
class OAuthClientToken extends AbstractOAuthToken
{
    /**
     * @var \sonrac\Auth\Entity\ClientInterface
     */
    private $client;

    /**
     * OAuthClientToken constructor.
     * @param \sonrac\Auth\Entity\ClientInterface $client
     * @param string $credentials
     * @param string $providerKey
     * @param array $scopes
     * @param array $roles
     */
    public function __construct(
        ClientInterface $client,
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
    public function getClient(): ClientInterface
    {
        return $this->client;
    }
}
