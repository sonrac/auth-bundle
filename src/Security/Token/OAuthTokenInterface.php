<?php

declare(strict_types=1);

namespace sonrac\Auth\Security\Token;

use Sonrac\OAuth2\Adapter\League\Entity\ClientEntityInterface;

/**
 * Interface OAuthTokenInterface
 * @package sonrac\Auth\Security\Token
 */
interface OAuthTokenInterface extends \Symfony\Component\Security\Core\Authentication\Token\TokenInterface
{
    /**
     * @return \Sonrac\OAuth2\Adapter\League\Entity\ClientEntityInterface
     */
    public function getClient(): ClientEntityInterface;

    /**
     * @return string
     */
    public function getProviderKey(): string;

    /**
     * @return \sonrac\Auth\Security\Scope\Scope[]
     */
    public function getScopes(): array;
}
