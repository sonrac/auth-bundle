<?php

declare(strict_types=1);

namespace sonrac\Auth\Tests\Functional;

/**
 * Class SecurityTest
 */
class SecurityTest extends AbstractSecurityControllerTest
{
    /**
     *
     */
    public function testSecurity()
    {
        $client = static::createClient();

        $client->request(
            'GET',
            '/api/security/test',
            array(),
            array(),
            array('HTTP_Authorization' => $this->getToken())
        );

        $this->assertEquals('{"status":true}', $client->getResponse()->getContent());
    }
}
