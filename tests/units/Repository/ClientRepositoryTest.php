<?php

declare(strict_types=1);

namespace sonrac\Auth\Tests\Units\Repository;

use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use sonrac\Auth\Entity\Client;
use sonrac\Auth\Repository\ClientRepository;
use sonrac\Auth\Tests\Units\BaseUnitTester;

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
     * Clients repository.
     *
     * @var \sonrac\Auth\Repository\ClientRepository
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
        $this->assertInstanceOf(ClientRepository::class, $this->repository);

        $data = $this->repository->find(['name' => 'Test Client']);
        $this->assertInstanceOf(Client::class, $data);
    }

    /**
     * Test client entity not found.
     */
    public function testGetClientEntityNotFound(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->repository->getClientEntity(1);
    }

    /**
     * Test grant type not allowed.
     */
    public function testGrantTypeNotAllowed(): void
    {
        $this->expectException(\LogicException::class);

        $this->repository->getClientEntity('Test Client', 'client');
    }

    /**
     * Test invalid secret.
     */
    public function testInvalidSecret(): void
    {
        $entity = $this->repository->getClientEntity('Test Client', null, null, false);
        $this->assertInstanceOf(Client::class, $entity);
        $this->expectException(\LogicException::class);
        $this->repository->getClientEntity('Test Client', null, null, true);
    }
}
