<?php

declare(strict_types=1);

namespace sonrac\Auth\Security\Token;

use sonrac\Auth\Entity\ClientInterface;

/**
 * Interface OAuthTokenInterface
 * @package sonrac\Auth\Security\Token
 */
interface OAuthTokenInterface extends \Symfony\Component\Security\Core\Authentication\Token\TokenInterface
{
    /**
     * @return \sonrac\Auth\Entity\ClientInterface
     */
    public function getClient(): ClientInterface;

    /**
     * @return string
     */
    public function getProviderKey(): string;

    /**
     * @return \sonrac\Auth\Security\Scope\Scope[]
     */
    public function getScopes(): array;
}
