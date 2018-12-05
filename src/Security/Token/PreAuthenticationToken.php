<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/5/18
 * Time: 12:06 AM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Security\Token;

/**
 * Class PreAuthenticationToken
 * @package Sonrac\OAuth2\Security\Token
 */
class PreAuthenticationToken extends AbstractPreAuthenticationToken
{
    /**
     * PreAuthenticationToken constructor.
     * @param string $user
     * @param string $client
     * @param string $providerKey
     * @param string|null $credentials
     * @param array $scopes
     * @param array $roles
     */
    public function __construct(
        string $user,
        string $client,
        string $providerKey,
        ?string $credentials = null,
        array $scopes = [],
        array $roles = []
    ) {
        parent::__construct($client, $providerKey, $credentials, $scopes, $roles);

        $this->setUser($user);
    }
}
