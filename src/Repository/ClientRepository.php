<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Sonrac\OAuth2\Adapter\Entity\ClientEntityInterface;
use Sonrac\OAuth2\Adapter\Repository\ClientRepositoryInterface;
use Sonrac\OAuth2\Entity\Client;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class ClientRepository
 * @package Sonrac\OAuth2\Repository
 *
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository implements ClientRepositoryInterface
{
    /**
     * ClientRepository constructor.
     * @param \Symfony\Bridge\Doctrine\RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Client::class);
    }

    /**
     * {@inheritdoc}
     */
    public function findClientEntityByIdentifier($identifier): ?ClientEntityInterface
    {
        return $this->findOneBy(['id' => $identifier]);
    }
}
