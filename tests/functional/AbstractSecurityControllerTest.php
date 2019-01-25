<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Tests\Functional;

use Sonrac\OAuth2\Bridge\Grant\ClientCredentialsGrant;

/**
 * Class AbstractSecurityControllerTest.
 */
abstract class AbstractSecurityControllerTest extends BaseFunctionalTester
{
    /**
     * {@inheritdoc}
     */
    protected $seeds = ['clients', 'users', 'scopes'];

    /**
     * @var array
     */
    protected $clearTablesList = [
        'oauth2_access_tokens',
        'oauth2_clients',
        'oauth2_refresh_tokens',
        'oauth2_auth_codes',
        'oauth2_users',
        'oauth2_scopes',
    ];

    /**
     * Access token.
     *
     * @var string
     */
    protected $token;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->token = $this->getToken();
    }

    /**
     * Get access token.
     */
    protected function getToken(): string
    {
        if ($this->token) {
            return $this->token;
        }

        $client = static::createClient();
        $client->request('POST', '/oauth/token', [
            'grant_type'    => ClientCredentialsGrant::TYPE,
            'client_id'     => 'test_client',
            'client_secret' => 'secret-key',
            'scope'         => 'default',
        ]);
        $response = $client->getResponse();

        $data = \json_decode($response->getContent(), true);

        return $data['access_token'];
    }
}
