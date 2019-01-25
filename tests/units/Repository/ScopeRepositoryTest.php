<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Tests\Units\Repository;

use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use Sonrac\OAuth2\Bridge\Grant\ClientCredentialsGrant;
use Sonrac\OAuth2\Tests\Units\BaseUnitTester;

/**
 * Class ScopeRepositoryTest.
 */
class ScopeRepositoryTest extends BaseUnitTester
{
    /**
     * {@inheritdoc}
     */
    protected $seeds = ['scopes', 'clients'];

    /**
     * @var array
     */
    protected $clearTablesList = ['oauth2_clients', 'oauth2_scopes'];

    /**
     * Scopes repository.
     *
     * @var \League\OAuth2\Server\Repositories\ScopeRepositoryInterface
     */
    protected $repository;

    /**
     * Clients repository.
     *
     * @var \League\OAuth2\Server\Repositories\ClientRepositoryInterface
     */
    protected $clientRepository;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository       = static::$container->get(ScopeRepositoryInterface::class);
        $this->clientRepository = static::$container->get(ClientRepositoryInterface::class);
    }

    /**
     * Test find scope.
     */
    public function testFindScope(): void
    {
        $scope = $this->repository->getScopeEntityByIdentifier('default');

        $this->assertInstanceOf(ScopeEntityInterface::class, $scope);
    }

    /**
     * Test find scope.
     */
    public function testFindScopeDoesNotExists(): void
    {
        $this->expectException(OAuthServerException::class);

        $this->repository->getScopeEntityByIdentifier('default-not-found');
    }

    /**
     * Test find scope.
     */
    public function testFinalize(): void
    {
        $client = $this->clientRepository->getClientEntity('test_client', null, null, false);

        $scopesOrigin = [
            $this->repository->getScopeEntityByIdentifier('default'),
            $this->repository->getScopeEntityByIdentifier('client'),
        ];
        $scopes = $this->repository->finalizeScopes($scopesOrigin, ClientCredentialsGrant::TYPE, $client);

        $this->assertEquals($scopes, $scopesOrigin);
    }
}
