<?php

namespace sonrac\Auth\Providers;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;

/**
 * Interface ClientProviderInterface
 */
interface ClientProviderInterface extends AuthenticationProviderInterface
{
    /**
     * Validate client secret.
     *
     * @param string                                               $secret
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface $client
     *
     * @return bool
     */
    public function validateClientSecret(string $secret, ClientEntityInterface $client): bool;
}