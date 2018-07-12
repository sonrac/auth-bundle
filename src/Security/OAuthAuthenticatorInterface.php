<?php

declare(strict_types=1);

namespace sonrac\Auth\Security;

use sonrac\Auth\Providers\ClientProviderInterface;
use Symfony\Component\Security\Guard\AuthenticatorInterface;

/**
 * Interface OAuthAuthenticatorInterface.
 */
interface OAuthAuthenticatorInterface extends AuthenticatorInterface
{
    /**
     * Get client.
     *
     * @return \sonrac\Auth\Providers\ClientProviderInterface
     */
    public function getClient(): ClientProviderInterface;
}
