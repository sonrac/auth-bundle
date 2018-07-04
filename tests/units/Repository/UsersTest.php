<?php

namespace sonrac\Auth\Tests\Units\Repository;

use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use sonrac\Auth\Entity\Client;
use sonrac\Auth\Entity\User;
use sonrac\Auth\Tests\Units\BaseUnitTester;

/**
 * Class UsersTest.
 */
class UsersTest extends BaseUnitTester
{
    /**
     * {@inheritdoc}
     */
    protected $seeds = ['users', 'clients'];

    /**
     * Users repository.
     *
     * @var \sonrac\Auth\Repository\Users
     */
    protected $repository;

    /**
     * Clients repository.
     *
     * @var \sonrac\Auth\Repository\Clients
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
     */
    public function testGetUserByCredentialsNotFound(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $client = $this->clientsRepository->find('Test Client');
        $this->repository->getUserEntityByUserCredentials(
            'user',
            'password',
            Client::GRANT_CLIENT_CREDENTIALS,
            $client
        );
    }

    /**
     * Test get user by credentials credentials are not equals.
     */
    public function testGetUserByCredentialsNotEquals(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $client = $this->clientsRepository->find('Test Client');
        $this->repository->getUserEntityByUserCredentials(
            'username',
            'password1',
            Client::GRANT_CLIENT_CREDENTIALS,
            $client
        );
    }

    /**
     * Test get user by credentials grant type not allowed.
     */
    public function testGetUserByCredentialsGrantTypeNotAllowed(): void
    {
        $this->expectException(\LogicException::class);

        $client = $this->clientsRepository->find('Test Client');
        $this->repository->getUserEntityByUserCredentials(
            'username',
            'password',
            Client::GRANT_CLIENT_CREDENTIALS.'1',
            $client
        );
    }

    /**
     * Test get user by credentials grant type not allowed.
     */
    public function testGetUserByCredentials(): void
    {
        $client = $this->clientsRepository->find('Test Client');
        $user   = $this->repository->getUserEntityByUserCredentials(
            'username',
            'password',
            Client::GRANT_CLIENT_CREDENTIALS,
            $client
        );

        $this->assertInstanceOf(User::class, $user);
    }
}
