<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Tests\Units\Repository;

use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Sonrac\OAuth2\Bridge\Grant\ClientCredentialsGrant;
use Sonrac\OAuth2\Tests\Units\BaseUnitTester;

/**
 * Class UserRepositoryTest.
 */
class UserRepositoryTest extends BaseUnitTester
{
    /**
     * {@inheritdoc}
     */
    protected $seeds = ['users', 'clients'];

    /**
     * @var array
     */
    protected $clearTablesList = ['oauth2_users', 'oauth2_clients'];

    /**
     * Users repository.
     *
     * @var \League\OAuth2\Server\Repositories\UserRepositoryInterface
     */
    protected $repository;

    /**
     * Clients repository.
     *
     * @var \League\OAuth2\Server\Repositories\ClientRepositoryInterface
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
        $this->expectException(OAuthServerException::class);

        $client = $this->clientsRepository->getClientEntity('test_client', null, null, false);

        $this->repository->getUserEntityByUserCredentials(
            'user',
            'password',
            ClientCredentialsGrant::TYPE,
            $client
        );
    }

    /**
     * Test get user by credentials credentials are not equals.
     */
    public function testGetUserByCredentialsNotEquals(): void
    {
        $this->expectException(OAuthServerException::class);

        $client = $this->clientsRepository->getClientEntity('test_client', null, null, false);

        $this->repository->getUserEntityByUserCredentials(
            'username',
            'password1',
            ClientCredentialsGrant::TYPE,
            $client
        );
    }

    /**
     * Test get user by credentials grant type not allowed.
     */
    public function testGetUserByCredentials(): void
    {
        $client = $this->clientsRepository->getClientEntity('test_client', null, null, false);

        $user = $this->repository->getUserEntityByUserCredentials(
            'username',
            'password',
            ClientCredentialsGrant::TYPE,
            $client
        );

        $this->assertInstanceOf(UserEntityInterface::class, $user);
    }
}
