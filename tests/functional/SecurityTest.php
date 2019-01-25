<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Tests\Functional;

/**
 * Class SecurityTest.
 */
class SecurityTest extends AbstractSecurityControllerTest
{
    public function testSecurity()
    {
        $client = static::createClient();

        $client->request(
            'GET',
            '/api/security/test',
            [],
            [],
            ['HTTP_Authorization' => $this->getToken()]
        );

        $this->assertJson($client->getResponse()->getContent());
        $this->assertEquals('{"status":true}', $client->getResponse()->getContent());
    }
}
