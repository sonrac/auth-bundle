<?php

declare(strict_types=1);

namespace sonrac\Auth\Providers;

use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Interface OAuthUserProviderInterface.
 */
interface OAuthUserProviderInterface extends UserProviderInterface
{
    /**
     * Load user by token.
     *
     * @param string $token
     *
     * @return mixed
     */
    public function loadByToken(string $token);
}
