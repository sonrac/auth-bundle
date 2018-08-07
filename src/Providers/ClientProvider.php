<?php

declare(strict_types=1);

namespace sonrac\Auth\Providers;

use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

/**
 * Class ClientProvider.
 */
class ClientProvider implements ClientProviderInterface
{
    /**
     * Client repository.
     *
     * @var \League\OAuth2\Server\Repositories\ClientRepositoryInterface|\sonrac\Auth\Repository\Clients
     */
    protected $clientRepository;

    /**
     * Entity manager.
     *
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * Cache item pools.
     *
     * @var \Psr\Cache\CacheItemPoolInterface
     */
    protected $cachePool;

    public function __construct(
        ClientRepositoryInterface $clientRepository,
        EntityManagerInterface $entity
    ) {
        $this->clientRepository = $clientRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function validateClientSecret(string $secret, ClientEntityInterface $client): bool
    {
        /** @var \sonrac\Auth\Entity\Client $client */
        return $secret === $client->getSecret();
    }

    /**
     * {@inheritdoc}
     */
    public function findByToken(string $token)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function findByName(string $name)
    {
        // TODO: Implement findByName() method.
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class): bool
    {
        return \in_array(ClientEntityInterface::class, \class_implements($class), true);
    }

    /**
     * {@inheritdoc}
     */
    public function refreshClient(ClientEntityInterface $client): ClientEntityInterface
    {
        $this->em->refresh($client);

        return $client;
    }
}
