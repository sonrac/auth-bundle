<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Tests\Units\Repository;

use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use Sonrac\OAuth2\Tests\Units\BaseUnitTester;

/**
 * Class AuthCodeRepositoryTest.
 */
class AuthCodeRepositoryTest extends BaseUnitTester
{
    /**
     * {@inheritdoc}
     */
    protected $seeds = ['authCodes', 'clients', 'scopes'];

    /**
     * @var array
     */
    protected $clearTablesList = ['oauth2_auth_codes', 'oauth2_clients', 'oauth2_scopes'];

    /**
     * Auth codes repository.
     *
     * @var \League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface
     */
    protected $repository;

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

        $this->repository       = static::$container->get(AuthCodeRepositoryInterface::class);
        $this->clientRepository = static::$container->get(ClientRepositoryInterface::class);
        $this->scopeRepository  = static::$container->get(ScopeRepositoryInterface::class);
    }

    /**
     * Test get new auth code.
     *
     * @throws \Exception
     *
     * @return \League\OAuth2\Server\Entities\AuthCodeEntityInterface
     */
    public function testGetNewAuthCode(): AuthCodeEntityInterface
    {
        $client = $this->clientRepository->getClientEntity('test_client', null, null, false);
        $scope  = $this->scopeRepository->getScopeEntityByIdentifier('default');

        $authCode = $this->repository->getNewAuthCode();
        $authCode->setClient($client);
        $authCode->addScope($scope);
        $authCode->setIdentifier('test_code1');
        $authCode->setExpiryDateTime((new \DateTime())->modify('+3600 seconds'));

        $this->assertInstanceOf(AuthCodeEntityInterface::class, $authCode);
        $this->assertInstanceOf(ClientEntityInterface::class, $authCode->getClient());
        $this->assertTrue('test_code1' === $authCode->getIdentifier());

        return $authCode;
    }

    /**
     * Test get new auth code.
     *
     * @param \League\OAuth2\Server\Entities\AuthCodeEntityInterface $authCode
     *
     * @throws \League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException
     *
     * @depends testGetNewAuthCode
     */
    public function testPersistAuthCode(AuthCodeEntityInterface $authCode): void
    {
        $this->repository->persistNewAuthCode($authCode);

        $this->seeCountInDatabase(2, 'oauth2_auth_codes');
        $this->seeCountInDatabase(1, 'oauth2_auth_codes', ['id' => 'test_code1']);
    }

    /**
     * Test persist token with same id.
     *
     * @param \League\OAuth2\Server\Entities\AuthCodeEntityInterface $authCode
     *
     * @throws \League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException
     *
     * @depends testGetNewAuthCode
     */
    public function testPersistNewAuthCodeDuplicateError(AuthCodeEntityInterface $authCode): void
    {
        $this->expectException(UniqueTokenIdentifierConstraintViolationException::class);

        $authCode->setIdentifier('test_code');

        $this->repository->persistNewAuthCode($authCode);
    }

    /**
     * Test get new auth code.
     */
    public function testRevokeAuthCode(): void
    {
        $this->repository->revokeAuthCode('test_code');

        $this->seeCountInDatabase(1, 'oauth2_auth_codes', ['id' => 'test_code', 'is_revoked' => true]);
    }

    /**
     * Test get new auth code.
     */
    public function testIsAuthCodeNotRevoked(): void
    {
        $this->assertFalse($this->repository->isAuthCodeRevoked('test_code'));
    }
}
