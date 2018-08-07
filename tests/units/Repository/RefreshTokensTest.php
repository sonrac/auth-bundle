<?php

declare(strict_types=1);

namespace sonrac\Auth\Tests\Units\Repository;

use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use sonrac\Auth\Entity\RefreshToken;
use sonrac\Auth\Tests\Units\BaseUnitTester;

/**
 * Class RefreshTokensTest.
 */
class RefreshTokensTest extends BaseUnitTester
{
    /**
     * {@inheritdoc}
     */
    protected $seeds = ['accessTokens', 'clients', 'refreshTokens', 'scopes'];

    /**
     * {@inheritdoc}
     */
    protected $clearTablesList = ['refresh_tokens'];

    /**
     * Refresh tokens repository.
     *
     * @var \sonrac\Auth\Repository\RefreshTokens
     */
    protected $repository;

    /**
     * Test get new token.
     */
    public function testGetNew(): RefreshToken
    {
        $token = $this->repository->getNewRefreshToken();

        $this->assertInstanceOf(RefreshToken::class, $token);
        $this->assertTrue($token->getCreatedAt() > 0);
        $this->assertTrue((int) $token->getUpdatedAt() === 0);

        return $token;
    }

    /**
     * Test persist token.
     *
     * @param \sonrac\Auth\Entity\RefreshToken $token
     *
     * @throws \League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException
     *
     * @depends testGetNew
     */
    public function testPersistToken(RefreshToken $token): void
    {
        $token->setIdentifier('token2');
        $token->setToken('test_token1');
        $token->setScopes(['test', 'test1']);
        $token->setExpireAt(\time());
        $this->repository->persistNewRefreshToken($token);

        $this->seeCountInDatabase(2, 'refresh_tokens');
        $this->seeCountInDatabase(1, 'refresh_tokens', ['refresh_token' => 'token2']);
    }

    /**
     * Test persist does not existing refresh token.
     */
    public function testRevokeTokenNotFound(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->repository->revokeRefreshToken('refresh-token-not-found');
    }

    /**
     * Test persist refresh token.
     */
    public function testRevokeToken(): void
    {
        $this->repository->revokeRefreshToken('refresh_token1');

        $this->seeCountInDatabase(1, 'refresh_tokens', [
            'refresh_token' => 'refresh_token1',
            'is_revoked'    => true,
        ]);
    }

    /**
     * Test is revoked refresh token.
     */
    public function testIsRevokedToken(): void
    {
        $this->assertFalse($this->repository->isRefreshTokenRevoked('refresh_token1'));
    }

    /**
     * Test is revoked refresh token not found.
     */
    public function testIsRevokedTokenNotFound(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->repository->isRefreshTokenRevoked('refresh_token-not-found');
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = static::$container->get(RefreshTokenRepositoryInterface::class);
    }
}
