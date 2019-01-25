<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/5/18
 * Time: 12:06 AM.
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Security\Token;

use League\OAuth2\Server\Entities\ClientEntityInterface;

/**
 * Class PreAuthenticationToken.
 */
class PreAuthenticationToken extends AbstractPreAuthenticationToken
{
    /**
     * PreAuthenticationToken constructor.
     *
     * @param string                                               $user
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface $client
     * @param string                                               $providerKey
     * @param string|null                                          $credentials
     * @param array                                                $scopes
     * @param array                                                $roles
     */
    public function __construct(
        string $user,
        ClientEntityInterface $client,
        string $providerKey,
        ?string $credentials = null,
        array $scopes = [],
        array $roles = []
    ) {
        parent::__construct($client, $providerKey, $credentials, $scopes, $roles);

        $this->setUser($user);
    }
}
