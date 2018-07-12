<?php

namespace sonrac\Auth\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use sonrac\Auth\Entity\User;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class AccessTokens.
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Users extends ServiceEntityRepository implements UserRepositoryInterface
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
     * @param \sonrac\Auth\Entity\Client $clientEntity
     *
     * @throws \InvalidArgumentException              if User not found or password does not verified
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \LogicException                        if grant type does not allowed
     */
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ) {
        $user = $this->loadByUsernameOrEmail($username);

        if (!\password_verify($password, $user->getPassword())) {
            throw new \InvalidArgumentException('User not found');
        }

        if (!\in_array($grantType, $clientEntity->getAllowedGrantTypes(), true)) {
            throw new \LogicException('Grant type does not allowed for client');
        }

        return $user;
    }

    /**
     * Load user by username or email.
     *
     * @param string $username
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \InvalidArgumentException
     *
     * @return \sonrac\Auth\Entity\User|\League\OAuth2\Server\Entities\UserEntityInterface
     */
    public function loadByUsernameOrEmail(string $username): ?UserEntityInterface
    {
        $user = $this->createQueryBuilder('user')
            ->orWhere('user.email = :username')
            ->orWhere('user.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult(Query::HYDRATE_OBJECT);

        if (!$user) {
            throw new \InvalidArgumentException('User not found');
        }

        return $user;
    }
}
