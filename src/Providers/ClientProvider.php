<?php

namespace sonrac\Auth\Providers;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class ClientProvider
 */
class ClientProvider implements ClientProviderInterface
{
    /**
     * Client repository.
     *
     * @var \League\OAuth2\Server\Repositories\ClientRepositoryInterface
     */
    protected $clientRepository;

    /**
     * Cache item pools.
     *
     * @var \Psr\Cache\CacheItemPoolInterface
     */
    protected $cachePool;

    public function __construct(
        ClientRepositoryInterface $clientRepository,
        CacheItemPoolInterface $pool
    ) {
        $this->clientRepository = $clientRepository;
        $this->cachePool = $pool;
    }

    /**
     * @inheritDoc
     */
    public function validateClientSecret(string $secret, ClientEntityInterface $client): bool
    {
        /** @var \sonrac\Auth\Entity\Client $client */
        return $secret === $client->getSecret();
    }

    /**
     * @inheritDoc
     *
     * @throws \InvalidArgumentException If attribute not found.
     */
    public function authenticate(TokenInterface $token)
    {
        $client = $this->clientRepository->getClientEntity(
            $token->getAttribute('client_id'),
            $token->getAttribute('grant_type'),
            $token->getAttribute('client_secret'),
            true
        );
    }

    /**
     * @inheritDoc
     */
    public function supports(TokenInterface $token)
    {
        $attributes = $token->getAttributes();

        return isset($attributes['client_id']) && !isset($attributes['username']);
    }
}
