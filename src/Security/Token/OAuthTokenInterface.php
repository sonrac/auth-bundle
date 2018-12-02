<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Security\Token;

use Sonrac\OAuth2\Adapter\League\Entity\ClientEntityInterface;

/**
 * Interface OAuthTokenInterface
 * @package Sonrac\OAuth2\Security\Token
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
     * @return \Sonrac\OAuth2\Security\Scope\Scope[]
     */
    public function getScopes(): array;
}
