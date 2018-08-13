<?php

declare(strict_types=1);

namespace sonrac\Auth\Providers;

use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class UserProvider.
 */
class UserProvider implements OAuthUserProviderInterface
{
    /**
     * @var UserRepositoryInterface|\sonrac\Auth\Repository\Users
     */
    private $userRepository;

    /**
     * UserProvider constructor.
     *
     * @param \League\OAuth2\Server\Repositories\UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \InvalidArgumentException
     */
    public function loadUserByUsername($username)
    {
        return $this->userRepository->loadByUsernameOrEmail($username);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        $this->userRepository->refreshUser($user);

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class): bool
    {
        return \in_array(UserProviderInterface::class, \class_implements($class), true);
    }

    /**
     * @inheritDoc
     */
    public function loadByToken(string $token)
    {
        $users = $this->userRepository->findBy(['api_token' => $token]);

        return count($users) ? $users[0] : null;
    }


}
