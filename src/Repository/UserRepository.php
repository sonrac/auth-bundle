<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Sonrac\OAuth2\Adapter\Entity\UserEntityInterface;
use Sonrac\OAuth2\Adapter\Repository\UserRepositoryInterface;
use Sonrac\OAuth2\Entity\User;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class UserRepository
 * @package Sonrac\OAuth2\Repository
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    /**
     * AccessTokens constructor.
     *
     * @param \Symfony\Bridge\Doctrine\RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findUserByUsername($username): ?UserEntityInterface
    {
        return $this->findByUsernameOrEmail($username);
    }

    /**
     * Load user by username or email.
     *
     * @param string $username
     *
     * @return \Sonrac\OAuth2\Entity\User|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByUsernameOrEmail(string $username): ?User
    {
        return $this->createQueryBuilder('user')
            ->orWhere('user.email = :username')
            ->orWhere('user.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
