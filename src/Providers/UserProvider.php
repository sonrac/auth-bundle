<?php

namespace sonrac\Auth\Providers;

use sonrac\Auth\Entity\User;
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
    public function supportsClass($class)
    {
        return User::class;
    }
}
