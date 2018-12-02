<?php

declare(strict_types=1);

namespace sonrac\Auth\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use sonrac\Auth\Entity\Client;
use Sonrac\OAuth2\Adapter\League\Entity\ClientEntityInterface;
use Sonrac\OAuth2\Adapter\League\Repository\ClientRepositoryInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class ClientRepository.
 *
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository implements ClientRepositoryInterface
{
    /**
     * Clients constructor.
     *
     * @param \Symfony\Bridge\Doctrine\RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Client::class);
    }

    /**
     * {@inheritdoc}
     *
     * @return \sonrac\Auth\Entity\Client|null
     */
    public function getClientEntityByIdentifier($identifier): ?ClientEntityInterface
    {
        return $this->findOneBy(['name' => $identifier]);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException If client found
     * @throws \LogicException           If client secret does not match or grant type is not allowed
     */
    public function getClientEntity(
        $clientIdentifier,
        $grantType = null,
        $clientSecret = null,
        $mustValidateSecret = true
    ) {
        $client = $this->getClientEntityByIdentifier($clientIdentifier);

        if (null === $client) {
            throw new \InvalidArgumentException('Client not found');
        }

        if ($grantType && !\in_array(\mb_strtolower($grantType), $client->getAllowedGrantTypes(), true)) {
            throw new \LogicException(
                'Grant type is not allowed for client application.' .
                \json_encode($client->getAllowedGrantTypes())
            );
        }

        if ($mustValidateSecret && $clientSecret !== $client->getSecret()) {
            throw new \LogicException('Client secret does not match');
        }

        return $client;
    }
}
