<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Tests\Units\Repository;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use Sonrac\OAuth2\Tests\Units\BaseUnitTester;

/**
 * Class RefreshTokenRepositoryTest.
 */
class RefreshTokenRepositoryTest extends BaseUnitTester
{
    /**
     * {@inheritdoc}
     */
    protected $seeds = ['accessTokens', 'clients', 'refreshTokens', 'scopes'];

    /**
     * {@inheritdoc}
     */
    protected $clearTablesList = ['oauth2_access_tokens', 'oauth2_clients', 'oauth2_refresh_tokens', 'oauth2_scopes'];

    /**
     * Refresh tokens repository.
     *
     * @var \League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface
     */
    protected $repository;

    /**
     * @var \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface
     */
    protected $accessTokenRepository;

    /**
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

        $this->repository            = static::$container->get(RefreshTokenRepositoryInterface::class);
        $this->accessTokenRepository = static::$container->get(AccessTokenRepositoryInterface::class);
        $this->clientRepository      = static::$container->get(ClientRepositoryInterface::class);
        $this->scopeRepository       = static::$container->get(ScopeRepositoryInterface::class);
    }

    /**
     * Test get new token.
     *
     * @throws \Exception
     */
    public function testGetNew(): RefreshTokenEntityInterface
    {
        $token = $this->repository->getNewRefreshToken();

        $client = $this->clientRepository->getClientEntity('test_client', null, null, false);
        $scope  = $this->scopeRepository->getScopeEntityByIdentifier('default');

        $accessToken = $this->accessTokenRepository->getNewToken($client, [$scope]);
        $accessToken->setIdentifier('test_access_token');
        $accessToken->setExpiryDateTime((new \DateTime())->modify('+3600 seconds'));
        $this->accessTokenRepository->persistNewAccessToken($accessToken);

        $token->setIdentifier('refresh_token_1');
        $token->setAccessToken($accessToken);
        $token->setExpiryDateTime((new \DateTime())->modify('+3600 seconds'));

        $this->assertInstanceOf(RefreshTokenEntityInterface::class, $token);
        $this->assertInstanceOf(AccessTokenEntityInterface::class, $token->getAccessToken());

        return $token;
    }

    /**
     * Test persist token.
     *
     * @param \League\OAuth2\Server\Entities\RefreshTokenEntityInterface $token
     *
     * @throws \League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException
     *
     * @depends testGetNew
     */
    public function testPersistToken(RefreshTokenEntityInterface $token): void
    {
        $this->repository->persistNewRefreshToken($token);

        $this->seeCountInDatabase(2, 'oauth2_refresh_tokens');
        $this->seeCountInDatabase(1, 'oauth2_refresh_tokens', ['id' => 'refresh_token_1']);
    }

    /**
     * Test persist token with same id.
     *
     * @param \League\OAuth2\Server\Entities\RefreshTokenEntityInterface $token
     *
     * @throws \League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException
     *
     * @depends testGetNew
     */
    public function testPersistTokenDuplicateError(RefreshTokenEntityInterface $token): void
    {
        $this->expectException(UniqueTokenIdentifierConstraintViolationException::class);

        $token->setIdentifier('refresh_token1');

        $this->repository->persistNewRefreshToken($token);
    }

    /**
     * Test revoke token.
     */
    public function testRevokeToken(): void
    {
        $this->repository->revokeRefreshToken('refresh_token1');

        $this->seeCountInDatabase(1, 'oauth2_refresh_tokens', ['id' => 'refresh_token1', 'is_revoked' => true]);
    }

    /**
     * Test check access token is revoked.
     */
    public function testTokenIsNotRevoked(): void
    {
        $this->assertFalse($this->repository->isRefreshTokenRevoked('refresh_token1'));
    }
}
