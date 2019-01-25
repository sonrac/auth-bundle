<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Sonrac\OAuth2\Adapter\Entity\ScopeEntityInterface;
use Sonrac\OAuth2\Adapter\Repository\ScopeRepositoryInterface;
use Sonrac\OAuth2\Entity\Scope;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class ScopeRepository
 * @package Sonrac\OAuth2\Repository
 *
 * @method Scope|null find($id, $lockMode = null, $lockVersion = null)
 * @method Scope|null findOneBy(array $criteria, array $orderBy = null)
 * @method Scope[]    findAll()
 * @method Scope[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScopeRepository extends ServiceEntityRepository implements ScopeRepositoryInterface
{
    /**
     * ScopeRepository constructor.
     * @param \Symfony\Bridge\Doctrine\RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Scope::class);
    }

    /**
     * {@inheritdoc}
     */
    public function findScopeEntityByIdentifier(string $identifier): ?ScopeEntityInterface
    {
        return $this->findOneBy(['id' => $identifier]);
    }
}
