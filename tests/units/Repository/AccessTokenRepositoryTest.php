<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Tests\Units\Repository;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use Sonrac\OAuth2\Tests\Units\BaseUnitTester;

/**
 * Class AccessTokenRepositoryTest
 * @package Sonrac\OAuth2\Tests\Units\Repository
 */
class AccessTokenRepositoryTest extends BaseUnitTester
{
    /**
     * {@inheritdoc}
     */
    protected $seeds = ['accessTokens', 'clients', 'scopes'];

    /**
     * {@inheritdoc}
     */
    protected $clearTablesList = ['oauth2_access_tokens', 'oauth2_clients', 'oauth2_scopes'];

    /**
     * AccessTokens repository.
     *
     * @var \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface
     */
    protected $repository;

    /**
     * Clients repository.
     *
     * @var \League\OAuth2\Server\Repositories\ClientRepositoryInterface
     */
    protected $clientRepository;

    /**
     * @var \League\OAuth2\Server\Repositories\ScopeRepositoryInterface
     */
    protected $scopeRepository;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = static::$container->get(AccessTokenRepositoryInterface::class);
        $this->clientRepository = static::$container->get(ClientRepositoryInterface::class);
        $this->scopeRepository = static::$container->get(ScopeRepositoryInterface::class);
    }

    /**
     * Test get new token.
     *
     * @return \League\OAuth2\Server\Entities\AccessTokenEntityInterface $token
     *
     * @throws \Exception
     */
    public function testGetNewToken(): AccessTokenEntityInterface
    {
        $client = $this->clientRepository->getClientEntity('test_client', null, null, false);

        $scope = $this->scopeRepository->getScopeEntityByIdentifier('default');

        $token = $this->repository->getNewToken($client, [$scope], 1);
        $token->setIdentifier('token-token');
        $token->setExpiryDateTime((new \DateTime())->modify('+3600 seconds'));

        $this->assertInstanceOf(AccessTokenEntityInterface::class, $token);
        $this->assertInstanceOf(ClientEntityInterface::class, $token->getClient());
        $this->assertCount(1, $token->getScopes());
        $this->assertTrue(1 === $token->getUserIdentifier());
        $this->assertTrue('token-token' === $token->getIdentifier());

        return $token;
    }

    /**
     * Test persist token.
     *
     * @param \League\OAuth2\Server\Entities\AccessTokenEntityInterface $token
     *
     * @throws \League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException
     *
     * @depends testGetNewToken
     */
    public function testPersistNewToken(AccessTokenEntityInterface $token): void
    {
        $this->repository->persistNewAccessToken($token);

        $this->seeCountInDatabase(3, 'oauth2_access_tokens');
        $this->seeCountInDatabase(1, 'oauth2_access_tokens', ['id' => 'token-token']);
    }

    /**
     * Test persist token with same id.
     *
     * @param \League\OAuth2\Server\Entities\AccessTokenEntityInterface $token
     *
     * @throws \League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException
     *
     * @depends testGetNewToken
     */
    public function testPersistNewTokenDuplicateError(AccessTokenEntityInterface $token): void
    {
        $this->expectException(UniqueTokenIdentifierConstraintViolationException::class);

        $token->setIdentifier('test_token');

        $this->repository->persistNewAccessToken($token);
    }

    /**
     * Test revoke token.
     */
    public function testRevokeToken(): void
    {
        $this->repository->revokeAccessToken('test_token');

        $this->seeCountInDatabase(1, 'oauth2_access_tokens', ['id' => 'test_token', 'is_revoked' => true]);
    }

    /**
     * Test check access token is revoked.
     */
    public function testAccessTokenIsNotRevoked(): void
    {
        $this->assertFalse($this->repository->isAccessTokenRevoked('test_token'));
    }
}
