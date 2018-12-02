<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Security\Factory;

use Psr\Http\Message\ServerRequestInterface;
use Sonrac\OAuth2\Exception\ClientIdentifierNotFoundException;
use Sonrac\OAuth2\Security\Token\OAuthClientToken;
use Sonrac\OAuth2\Security\Token\OAuthTokenInterface;
use Sonrac\OAuth2\Security\Token\OAuthUserToken;
use Sonrac\OAuth2\Adapter\League\Repository\ClientRepositoryInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class OAuthTokenFactory
 * @package Sonrac\OAuth2\Security\Factory
 */
class OAuthTokenFactory
{
    /**
     * @var \Sonrac\OAuth2\Adapter\League\Repository\ClientRepositoryInterface
     */
    private $clientRepository;

    /**
     * @var \Symfony\Component\Security\Core\User\UserProviderInterface
     */
    private $userProvider;

    /**
     * OAuthTokenFactory constructor.
     * @param \Sonrac\OAuth2\Adapter\League\Repository\ClientRepositoryInterface $clientRepository
     * @param \Symfony\Component\Security\Core\User\UserProviderInterface $userProvider
     */
    public function __construct(ClientRepositoryInterface $clientRepository, UserProviderInterface $userProvider)
    {
        $this->clientRepository = $clientRepository;
        $this->userProvider = $userProvider;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param string $providerKey
     *
     * @return \Sonrac\OAuth2\Security\Token\OAuthTokenInterface
     */
    public function createFromRequest(ServerRequestInterface $request, string $providerKey): OAuthTokenInterface
    {
        $clientId = $request->getAttribute('oauth_client_id');
        $userId = $request->getAttribute('oauth_user_id');
        $scopes = $request->getAttribute('oauth_scopes');

        $client = $this->clientRepository->getClientEntityByIdentifier($clientId);

        if (null === $client) {
            throw new ClientIdentifierNotFoundException($clientId);
        }

        $user = (null !== $userId && '' !== $userId) ? $this->userProvider->loadUserByUsername($userId) : null;

        if (null === $user) {
            $token = new OAuthClientToken($client, $client->getSecret(), $providerKey, $scopes);
        } else {
            $token = new OAuthUserToken($client, $user, $user->getPassword(), $providerKey, $scopes, $user->getRoles());
        }

        $token->setAttribute('access_token_id', $request->getAttribute('oauth_access_token_id'));

        return $token;
    }
}
