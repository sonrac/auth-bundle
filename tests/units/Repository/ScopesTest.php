<?php

namespace sonrac\Auth\Tests\Units\Repository;

use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use sonrac\Auth\Entity\Client;
use sonrac\Auth\Entity\Scope;
use sonrac\Auth\Tests\Units\BaseUnitTester;

/**
 * Class ScopesTest
 */
class ScopesTest extends BaseUnitTester
{
    /**
     * {@inheritdoc}
     */
    protected $seeds = ['scopes', 'clients'];

    /**
     * Scopes repository.
     *
     * @var \sonrac\Auth\Repository\Scopes
     */
    protected $repository;

    /**
     * Clients repository.
     *
     * @var \sonrac\Auth\Repository\Clients
     */
    protected $clientRepository;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = static::$container->get(ScopeRepositoryInterface::class);
        $this->clientRepository = static::$container->get(ClientRepositoryInterface::class);
    }

    /**
     * Test find scope.
     */
    public function testFindScope(): void
    {
        $scope = $this->repository->getScopeEntityByIdentifier('default');

        $this->assertInstanceOf(Scope::class, $scope);
    }

    /**
     * Test find scope.
     */
    public function testFindScopeDoesNotExists(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->repository->getScopeEntityByIdentifier('default-not-found');
    }

    /**
     * Test find scope.
     */
    public function testFinalize(): void
    {
        $client = $this->clientRepository->find('Test Client');

        $scopesOrigin = $this->repository->findAll();
        $scopes = $this->repository->finalizeScopes($scopesOrigin, Client::GRANT_CLIENT_CREDENTIALS, $client);

        $this->assertEquals($scopes, $scopesOrigin);
    }
}