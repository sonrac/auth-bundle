<?php

declare(strict_types=1);

namespace sonrac\Auth\Tests\Units\Repository;

use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use sonrac\Auth\Entity\User;
use sonrac\Auth\Tests\Units\BaseUnitTester;
use Sonrac\OAuth2\Adapter\League\Grant\ClientCredentialsGrant;

/**
 * Class UserRepositoryTest
 * @package sonrac\Auth\Tests\Units\Repository
 */
class UserRepositoryTest extends BaseUnitTester
{
    /**
     * {@inheritdoc}
     */
    protected $seeds = ['users', 'clients'];

    /**
     * Users repository.
     *
     * @var \sonrac\Auth\Repository\UserRepository
     */
    protected $repository;

    /**
     * Clients repository.
     *
     * @var \sonrac\Auth\Repository\ClientRepository
     */
    protected $clientsRepository;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository        = static::$container->get(UserRepositoryInterface::class);
        $this->clientsRepository = static::$container->get(ClientRepositoryInterface::class);
    }

    /**
     * Test get user by credentials not found.
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function testGetUserByCredentialsNotFound(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $client = $this->clientsRepository->find('Test Client');
        $this->repository->getUserEntityByUserCredentials(
            'user',
            'password',
            ClientCredentialsGrant::TYPE,
            $client
        );
    }

    /**
     * Test get user by credentials credentials are not equals.
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function testGetUserByCredentialsNotEquals(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $client = $this->clientsRepository->find('Test Client');
        $this->repository->getUserEntityByUserCredentials(
            'username',
            'password1',
            ClientCredentialsGrant::TYPE,
            $client
        );
    }

    /**
     * Test get user by credentials grant type not allowed.
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function testGetUserByCredentialsGrantTypeNotAllowed(): void
    {
        $this->expectException(\LogicException::class);

        $client = $this->clientsRepository->find('Test Client');
        $this->repository->getUserEntityByUserCredentials(
            'username',
            'password',
            ClientCredentialsGrant::TYPE.'1',
            $client
        );
    }

    /**
     * Test get user by credentials grant type not allowed.
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function testGetUserByCredentials(): void
    {
        $client = $this->clientsRepository->find('Test Client');
        $user   = $this->repository->getUserEntityByUserCredentials(
            'username',
            'password',
            ClientCredentialsGrant::TYPE,
            $client
        );

        $this->assertInstanceOf(User::class, $user);
    }
}
