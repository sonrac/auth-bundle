<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sonrac\OAuth2\Adapter\Entity\ClientEntityInterface;
use Sonrac\OAuth2\Adapter\Exception\NotUniqueClientIdentifierException;
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
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createClientEntity(
        string $name,
        string $secret,
        array $grantTypes,
        array $redirectUris,
        $identifier = null,
        array $options = []
    ): ClientEntityInterface {
        if (null === $identifier || false === \is_string($identifier) || '' === $identifier) {
            throw new \InvalidArgumentException('Argument "$identifier" must be a not empty string.');
        }

        $client = new Client();

        $client->setId($identifier);
        $client->setName($name);
        $client->setSecret($secret);
        $client->setDescription($options['description'] ?? '');
        $client->setAllowedGrantTypes($grantTypes);
        $client->setRedirectUris($redirectUris);
        $client->setCreatedAt(\time());

        try {
            $this->_em->persist($client);
            $this->_em->flush();
        } catch (UniqueConstraintViolationException $exception) {
            throw new NotUniqueClientIdentifierException(
                sprintf('Client with id "%s" already exists', $identifier),
                $exception->getCode(),
                $exception
            );
        }

        return $client;
    }

    /**
     * {@inheritdoc}
     */
    public function findClientEntityByIdentifier($identifier): ?ClientEntityInterface
    {
        return $this->findOneBy(['id' => $identifier]);
    }
}
