<?php

declare(strict_types=1);

namespace sonrac\Auth\Tests\Functional;

use Sonrac\OAuth2\Adapter\League\Grant\ClientCredentialsGrant;
use Sonrac\OAuth2\Adapter\League\Grant\PasswordGrant;
use Sonrac\OAuth2\Adapter\League\Grant\RefreshTokenGrant;

/**
 * Class AuthorizeTest.
 */
class AuthorizeTest extends BaseFunctionalTester
{
    /**
     * {@inheritdoc}
     */
    protected $seeds = ['clients', 'users', 'scopes'];

    /**
     * {@inheritdoc}
     */
    protected $clearTablesList = ['access_tokens', 'clients', 'refresh_tokens', 'auth_codes'];

    /**
     * Test error grant type.
     */
    public function testErrorClientAuth(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/auth/token');
        $response = $client->getResponse();

        $data = \json_decode($response->getContent(), true);

        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('error', $data);
        $this->assertArrayHasKey('message', $data);
        $this->assertArrayHasKey('hint', $data);

        $this->assertContains('unsupported_grant_type', $data['error']);
    }

    /**
     * Test error client.
     */
    public function testErrorClient(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/auth/token', [
            'grant_type' => ClientCredentialsGrant::TYPE,
        ]);
        $response = $client->getResponse();

        $data = \json_decode($response->getContent(), true);

        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('error', $data);
        $this->assertArrayHasKey('message', $data);
        $this->assertArrayHasKey('hint', $data);
        $this->assertContains('invalid_request', $data['error']);
    }

    /**
     * Test client success.
     */
    public function testClientAuthSuccess(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/auth/token', [
            'grant_type'    => ClientCredentialsGrant::TYPE,
            'client_id'     => 'Test Client',
            'client_secret' => 'secret-key',
            'scope'         => 'default',
        ]);
        $response = $client->getResponse();

        $data = \json_decode($response->getContent(), true);

        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('token_type', $data);
        $this->assertArrayHasKey('expires_in', $data);
        $this->assertArrayHasKey('access_token', $data);
        $this->assertEquals('bearer', \mb_strtolower($data['token_type']));

        $this->checkToken();
    }

    /**
     * Test client success.
     *
     * @return array
     */
    public function testUserGrantSuccess(): array
    {
        $client = static::createClient();
        $client->request('POST', '/api/auth/token', [
            'grant_type'    => PasswordGrant::TYPE,
            'client_id'     => 'Test Client',
            'client_secret' => 'secret-key',
            'scope'         => 'default',
            'username'      => 'username',
            'password'      => 'password',
        ]);
        $response = $client->getResponse();

        $data = \json_decode($response->getContent(), true);

        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('token_type', $data);
        $this->assertArrayHasKey('expires_in', $data);
        $this->assertArrayHasKey('access_token', $data);
        $this->assertArrayHasKey('refresh_token', $data);
        $this->assertEquals('bearer', \mb_strtolower($data['token_type']));

        $tokens   = $this->checkToken(PasswordGrant::TYPE, true);
        $tokens[] = $data['refresh_token'];

        return $tokens;
    }

    /**
     * Test client success.
     *
     * @param array $tokens
     *
     * @throws \Doctrine\DBAL\DBALException
     *
     * @depends testUserGrantSuccess
     */
    public function testRefreshGrant(array $tokens): void
    {
        static::$container->get('doctrine.dbal.default_connection')
            ->insert('access_tokens', $tokens[0][0]);
        static::$container->get('doctrine.dbal.default_connection')
            ->insert('refresh_tokens', $tokens[1][0]);

        $client = static::createClient();
        $client->request('POST', '/api/auth/token', [
            'grant_type'    => RefreshTokenGrant::TYPE,
            'client_id'     => 'Test Client',
            'refresh_token' => $tokens[2],
            'client_secret' => 'secret-key',
            'scope'         => 'default',
        ]);
        $response = $client->getResponse();

        $data = \json_decode($response->getContent(), true);

        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('token_type', $data);
        $this->assertArrayHasKey('expires_in', $data);
        $this->assertArrayHasKey('access_token', $data);
        $this->assertArrayHasKey('refresh_token', $data);
        $this->assertEquals('bearer', \mb_strtolower($data['token_type']));

        $this->assertCount(1, $this->getRevoked());
        $this->assertCount(1, $this->getRevoked('refresh_tokens'));
        $this->assertCount(1, $this->getRevoked('access_tokens', 0));
        $this->assertCount(1, $this->getRevoked('refresh_tokens', 0));
    }

    /**
     * Test implicit grant authorize.
     */
//    public function testImplicitGrant(): void
//    {
//        $client = static::createClient();
//        $client->request(
//            'GET',
//            '/auth/authorize?'.\http_build_query([
//                'grant_type'    => Client::GRANT_IMPLICIT,
//                'client_id'     => 'Test Client',
//                'redirect_uri'  => 'http://test.com',
//                'scope'         => 'default',
//                'response_type' => Client::RESPONSE_TYPE_TOKEN,
//            ])
//        );
//        $response = $client->getResponse();
//
//        $this->assertArrayHasKey('location', $response->headers->all());
//
//        $uriParts = \parse_url($location = $response->headers->get('Location'));
//
//        \parse_str($uriParts['query'], $parameters);
//
//        $this->assertInternalType('array', $parameters);
//        $this->assertArrayHasKey('token_type', $parameters);
//        $this->assertArrayHasKey('expires_in', $parameters);
//        $this->assertArrayHasKey('access_token', $parameters);
//        $this->assertEquals('bearer', \mb_strtolower($parameters['token_type']));
//
//        $this->checkToken(Client::GRANT_IMPLICIT);
//    }

    /**
     * Test implicit grant authorize.
     */
//    public function testAuthCodeGrant(): void
//    {
//        $client = static::createClient();
//        $client->request(
//            'GET',
//            '/auth/authorize?'.\http_build_query([
//                'grant_type'    => Client::GRANT_AUTH_CODE,
//                'client_id'     => 'Test Client',
//                'redirect_uri'  => 'http://test.com',
//                'scope'         => 'default',
//                'response_type' => Client::RESPONSE_TYPE_CODE,
//                'state'         => 'sample-csrf',
//            ])
//        );
//        $response = $client->getResponse();
//
//        $this->assertArrayHasKey('location', $response->headers->all());
//
//        $uriParts = \parse_url($location = $response->headers->get('Location'));
//
//        \parse_str($uriParts['query'], $parameters);
//
//        $this->assertInternalType('array', $parameters);
//        $this->assertArrayHasKey('code', $parameters);
//        $this->assertArrayHasKey('state', $parameters);
//        $this->assertEquals('sample-csrf', $parameters['state']);
//
//        $code = static::$container->get('doctrine.dbal.default_connection')
//            ->createQueryBuilder()
//            ->select(['*'])
//            ->from('auth_codes', 'ac')
//            ->execute()
//            ->fetchAll();
//
//        $this->assertCount(1, $code);
//
//        $client->request('POST', '/auth/token', [
//            'grant_type'    => Client::GRANT_AUTH_CODE,
//            'client_id'     => 'Test Client',
//            'client_secret' => 'secret-key',
//            'scope'         => 'default',
//            'redirect_uri'  => 'http://test.com',
//            'code'          => $parameters['code'],
//        ]);
//
//        $response = $client->getResponse();
//
//        $data = \json_decode($response->getContent(), true);
//
//        $this->assertInternalType('array', $data);
//        $this->assertArrayHasKey('token_type', $data);
//        $this->assertArrayHasKey('expires_in', $data);
//        $this->assertArrayHasKey('access_token', $data);
//        $this->assertEquals('bearer', \mb_strtolower($data['token_type']));
//
//        $this->checkToken(Client::GRANT_AUTH_CODE);
//    }

    /**
     * Check token.
     *
     * @param string $grantType
     * @param bool   $withRefresh
     *
     * @return array
     */
    private function checkToken($grantType = ClientCredentialsGrant::TYPE, $withRefresh = false): array
    {
        $token = static::$container->get('doctrine.dbal.default_connection')
            ->createQueryBuilder()
            ->select(['*'])
            ->from('access_tokens', 'ac')
            ->execute()
            ->fetchAll();

        $this->assertCount(1, $token);

        $this->assertEquals($grantType, $token[0]['grant_type']);
        $this->assertEquals('["default"]', $token[0]['token_scopes']);

        $refresh = null;

        if ($withRefresh) {
            $refresh = static::$container->get('doctrine.dbal.default_connection')
                ->createQueryBuilder()
                ->select(['*'])
                ->from('refresh_tokens', 'ac')
                ->execute()
                ->fetchAll();

            $this->assertCount(1, $refresh);

            $this->assertEquals($token[0]['token_scopes'], $refresh[0]['token_scopes']);
        }

        return [$token, $refresh];
    }

    /**
     * Get revoked tokens.
     *
     * @param string $table
     * @param bool   $revoked
     *
     * @return array
     */
    private function getRevoked($table = 'access_tokens', $revoked = true): array
    {
        return static::$container->get('doctrine.dbal.default_connection')
            ->createQueryBuilder()
            ->select('*')
            ->from($table)
            ->where('is_revoked = :revoked')
            ->setParameter('revoked', $revoked)
            ->execute()
            ->fetchAll();
    }
}
