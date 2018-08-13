<?php

declare(strict_types=1);

namespace sonrac\Auth\Providers;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Interface ClientProviderInterface.
 */
interface ClientProviderInterface extends UserProviderInterface
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

    /**
     * Find client by access token.
     *
     * @param string $token
     *
     * @return mixed
     */
    public function findByToken(string $token);

    /**
     * Find client by client name.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function findByName(string $name);

    /**
     * Refreshes the client.
     *
     * It is up to the implementation to decide if the client data should be
     * totally reloaded (e.g. from the database), or if the ClientEntityInterface
     * object can just be merged into some internal array of clients / identity
     * map.
     *
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface $client
     *
     * @return \League\OAuth2\Server\Entities\ClientEntityInterface
     */
    public function refreshClient(ClientEntityInterface $client): ClientEntityInterface;
}
