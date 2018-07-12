<?php

declare(strict_types=1);

namespace sonrac\Auth\Providers;

use sonrac\Auth\Repository\Users;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class UserProvider.
 */
class UserProvider extends Users implements UserProviderInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \InvalidArgumentException
     */
    public function loadUserByUsername($username)
    {
        return $this->loadByUsernameOrEmail($username);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function refreshUser(UserInterface $user)
    {
        $this->getEntityManager()->refresh($user);

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class): bool
    {
        return \in_array(UserProviderInterface::class, class_implements($class), true);
    }
}
