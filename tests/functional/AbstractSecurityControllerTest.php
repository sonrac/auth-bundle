<?php

declare(strict_types=1);

namespace sonrac\Auth\Tests\Functional;
use sonrac\Auth\Entity\Client;

/**
 * Class AbstractSecurityControllerTest
 */
abstract class AbstractSecurityControllerTest extends BaseFunctionalTester
{
    /**
     * {@inheritdoc}
     */
    protected $seeds = ['clients', 'users', 'scopes'];

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
    protected function getToken(): string {
        if ($this->token) {
            return $this->token;
        }

        $client = static::createClient();
        $client->request('POST', '/auth/token', [
            'grant_type'    => Client::GRANT_CLIENT_CREDENTIALS,
            'client_id'     => 'Test Client',
            'client_secret' => 'secret-key',
            'scope'         => 'default',
        ]);
        $response = $client->getResponse();

        $data = \json_decode($response->getContent(), true);

        return $data['access_token'];
    }
}
