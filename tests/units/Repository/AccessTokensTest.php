<?php

namespace sonrac\Auth\Tests\Units\Repository;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use sonrac\Auth\Entity\AccessToken;
use sonrac\Auth\Entity\Client;
use sonrac\Auth\Tests\Units\BaseUnitTester;

/**
 * Class AccessTokensTest.
 */
class AccessTokensTest extends BaseUnitTester
{
    /**
     * {@inheritdoc}
     */
    protected $clearTablesList = ['access_tokens'];

    /**
     * {@inheritdoc}
     */
    protected $seeds = ['accessTokens', 'clients', 'scopes'];

    /**
     * AccessTokens repository.
     *
     * @var \sonrac\Auth\Repository\AccessTokens
     */
    protected $repository;

    /**
     * Clients repository.
     *
     * @var \sonrac\Auth\Repository\Clients
     */
    protected $clientRepository;

    /**
     * Scopes repository.
     *
     * @var \sonrac\Auth\Repository\Clients
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
     * @return \sonrac\Auth\Entity\AccessToken $token
     */
    public function testGetNewToken(): AccessToken
    {
        $client = $this->clientRepository->find('Test Client');
        $scopes = $this->scopeRepository->findAll();
        $token = $this->repository->getNewToken($client, $scopes, 1);
        $token->setIdentifier('token-token');
        $token->setGrantType(Client::GRANT_CLIENT_CREDENTIALS);
        $token->setExpiryDateTime((new \DateTime())->modify('+3600 seconds'));

        $this->assertInstanceOf(AccessToken::class, $token);

        return $token;
    }

    /**
     * Test persist token.
     *
     * @param \sonrac\Auth\Entity\AccessToken $token
     *
     * @depends testGetNewToken
     */
    public function testPersistNewToken(AccessToken $token): void
    {
        $token->setUpdatedAt(1);
        $this->repository->persistNewAccessToken($token);
        $this->seeCountInDatabase(3, 'access_tokens');
        $this->seeCountInDatabase(1, 'access_tokens', ['token' => 'token-token']);
    }

    /**
     * Test revoke token.
     *
     * @param \sonrac\Auth\Entity\AccessToken $token
     *
     * @depends testGetNewToken
     */
    public function testRevokeToken(AccessToken $token): void
    {
        $this->testPersistNewToken($token);
        $this->repository->revokeAccessToken($token->getIdentifier());

        $this->seeCountInDatabase(1, 'access_tokens', ['token' => 'token-token', 'is_revoked' => true]);
    }

    /**
     * Test revoke token.
     */
    public function testRevokeTokenNotFound(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->repository->revokeAccessToken('token-not-find');
    }

    /**
     * Test check access token revoked not found.
     */
    public function testCheckAccessTokenRevokedNotFound(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->repository->isAccessTokenRevoked('token-not-found');
    }

    /**
     * Test check access token revoked not found.
     */
    public function testCheckAccessTokenRevoked(): void
    {
        $this->assertFalse($this->repository->isAccessTokenRevoked('test_token'));
    }
}
