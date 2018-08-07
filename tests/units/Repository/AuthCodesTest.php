<?php

declare(strict_types=1);

namespace sonrac\Auth\Tests\Units\Repository;

use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use sonrac\Auth\Tests\Units\BaseUnitTester;

/**
 * Class AuthCodesTest.
 */
class AuthCodesTest extends BaseUnitTester
{
    /**
     * {@inheritdoc}
     */
    protected $seeds = ['authCodes'];

    /**
     * Auth codes repository.
     *
     * @var \sonrac\Auth\Repository\AuthCodes
     */
    protected $repository;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = static::$container->get(AuthCodeRepositoryInterface::class);
    }

    /**
     * Test get new auth code.
     */
    public function testGetNewAuthCode(): void
    {
        $this->repository->getNewAuthCode();

        $this->assertFalse(false);
    }

    /**
     * Test get new auth code.
     *
     * @throws \League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException
     */
    public function testPersistAuthCode(): void
    {
        /** @var \sonrac\Auth\Entity\AuthCode $code */
        $code = $this->repository->find('test_code');
        $this->repository->persistNewAuthCode($code);

        $this->assertFalse(false);
    }

    /**
     * Test get new auth code.
     */
    public function testRevokeAuthCode(): void
    {
        $this->repository->revokeAuthCode('test_code');

        $this->assertFalse(false);
    }

    /**
     * Test get new auth code.
     */
    public function testIsAuthCodeRevoked(): void
    {
        $this->repository->isAuthCodeRevoked('test_code');

        $this->assertFalse(false);
    }
}
