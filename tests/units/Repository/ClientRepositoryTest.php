<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Tests\Units\Repository;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use Sonrac\OAuth2\Bridge\Grant\ClientCredentialsGrant;
use Sonrac\OAuth2\Tests\Units\BaseUnitTester;

/**
 * Class ClientRepositoryTest
 * @package sonrac\Auth\Tests\Units\Repository
 */
class ClientRepositoryTest extends BaseUnitTester
{
    /**
     * {@inheritdoc}
     */
    protected $seeds = ['clients'];

    /**
     * @var array
     */
    protected $clearTablesList = ['oauth2_clients'];

    /**
     * Clients repository.
     *
     * @var \League\OAuth2\Server\Repositories\ClientRepositoryInterface
     */
    protected $repository;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = static::$container->get(ClientRepositoryInterface::class);
    }

    /**
     * Test client repository.
     */
    public function testGetClientEntity(): void
    {
        $client = $this->repository->getClientEntity('test_client', null, null, false);

        $this->assertInstanceOf(ClientEntityInterface::class, $client);
    }

    /**
     * Test client repository get entity with valid credentials.
     */
    public function testGetClientEntityWithValidSecret(): void
    {
        $client = $this->repository->getClientEntity('test_client', null, 'secret-key', true);

        $this->assertInstanceOf(ClientEntityInterface::class, $client);
    }

    /**
     * Test client repository get entity with invalid credentials.
     */
    public function testGetClientEntityWithInvalidSecret(): void
    {
        $this->expectException(OAuthServerException::class);

        $this->repository->getClientEntity('test_client');
    }

    /**
     * Test client repository get entity with supported grant type.
     */
    public function testGetClientWithSupportedGrant(): void
    {
        $client = $this->repository->getClientEntity('test_client', ClientCredentialsGrant::TYPE, null, false);

        $this->assertInstanceOf(ClientEntityInterface::class, $client);
    }

    /**
     * Test client repository get entity with not supported grant type.
     */
    public function testGetClientWithNotSupportedGrant(): void
    {
        $this->expectException(OAuthServerException::class);

        $this->repository->getClientEntity('test_client', 'invalid');
    }
}
