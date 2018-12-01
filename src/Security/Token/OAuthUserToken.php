<?php

declare(strict_types=1);

namespace sonrac\Auth\Security\Token;

use sonrac\Auth\Entity\ClientInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class OAuthToken
 * @package sonrac\Auth\Security\Token
 */
class OAuthUserToken extends AbstractOAuthToken
{
    /**
     * @var \sonrac\Auth\Entity\ClientInterface
     */
    private $client;

    /**
     * OAuthUserToken constructor.
     * @param \sonrac\Auth\Entity\ClientInterface $client
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @param string $credentials
     * @param string $providerKey
     * @param array $scopes
     * @param array $roles
     */
    public function __construct(
        ClientInterface $client,
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
    public function getClient(): ClientInterface
    {
        return $this->client;
    }
}
