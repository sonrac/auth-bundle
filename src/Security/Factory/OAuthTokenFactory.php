<?php

declare(strict_types=1);

namespace sonrac\Auth\Security\Factory;

use Psr\Http\Message\ServerRequestInterface;
use sonrac\Auth\Exceptions\InvalidUserProvidedException;
use sonrac\Auth\Repository\ClientRepositoryInterface;
use sonrac\Auth\Repository\UserRepositoryInterface;
use sonrac\Auth\Security\Token\OAuthClientToken;
use sonrac\Auth\Security\Token\OAuthTokenInterface;
use sonrac\Auth\Security\Token\OAuthUserToken;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class OAuthTokenFactory
 * @package sonrac\Auth\Security\Factory
 */
class OAuthTokenFactory
{
    /**
     * @var \sonrac\Auth\Repository\ClientRepositoryInterface
     */
    private $clientRepository;

    /**
     * @var \sonrac\Auth\Repository\UserRepositoryInterface
     */
    private $userRepository;

    /**
     * OAuthTokenFactory constructor.
     * @param \sonrac\Auth\Repository\ClientRepositoryInterface $clientRepository
     * @param \sonrac\Auth\Repository\UserRepositoryInterface $userRepository
     */
    public function __construct(ClientRepositoryInterface $clientRepository, UserRepositoryInterface $userRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param string $providerKey
     *
     * @return \sonrac\Auth\Security\Token\OAuthTokenInterface
     */
    public function createFromRequest(ServerRequestInterface $request, string $providerKey): OAuthTokenInterface
    {
        $clientId = $request->getAttribute('oauth_client_id');
        $userId = $request->getAttribute('oauth_user_id');
        $scopes = $request->getAttribute('oauth_scopes');

        $client = $this->clientRepository->findByIdentifier($clientId);
        $user = (null !== $userId && '' !== $userId) ? $this->findUser($userId) : null;

        if (null === $user) {
            $token = new OAuthClientToken($client, $client->getSecret(), $providerKey, $scopes);
        } else {
            $token = new OAuthUserToken($client, $user, $user->getPassword(), $providerKey, $scopes, $user->getRoles());
        }

        $token->setAttribute('access_token_id', $request->getAttribute('oauth_access_token_id'));

        return $token;
    }

    /**
     * @param string|int $userId
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    private function findUser($userId): UserInterface
    {
        $user = $this->userRepository->findByIdentifier($userId);

        if (null === $user) {
            throw new InvalidUserProvidedException();
        }

        return $user;
    }
}
