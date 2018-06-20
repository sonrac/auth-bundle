<?php

namespace sonrac\AuthBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use sonrac\AuthBundle\Entity\Scope;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class Scopes.
 * Scopes repository.
 *
 * @method Scope|null find($id, $lockMode = null, $lockVersion = null)
 * @method Scope|null findOneBy(array $criteria, array $orderBy = null)
 * @method Scope[]    findAll()
 * @method Scope[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Scopes extends ServiceEntityRepository implements ScopeRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Scope::class);
    }

    /**
     * @inheritDoc
     *
     * @throws \InvalidArgumentException
     */
    public function getScopeEntityByIdentifier($identifier)
    {
        $scope = $this->find($identifier);

        if (!$scope) {
            throw new \InvalidArgumentException('Scope not find');
        }

        return $scope;
    }

    /**
     * @inheritDoc
     */
    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null
    ) {
        return $scopes;
    }
}
