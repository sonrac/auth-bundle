<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Tests\Units\Commands;

use Sonrac\OAuth2\Tests\Units\BaseUnitTester;
use Symfony\Component\Console\Exception\InvalidOptionException;

/**
 * Class GenerateClientTest.
 */
class GenerateClientTest extends BaseUnitTester
{
    /**
     * @var array
     */
    protected $seeds = ['clients'];

    /**
     * @var array
     */
    protected $clearTablesList = ['oauth2_clients'];

    /**
     * Test generate client.
     */
    public function testGenerateClient(): void
    {
        $this->runCommand('sonrac_oauth:generate:client', [
            '--identifier' => 'client_tester',
            '--name' => 'client_tester',
        ]);

        $this->seeCountInDatabase(1, 'oauth2_clients', ['id' => 'client_tester']);
    }

    /**
     * Test generate with not unique identifier.
     */
    public function testGenerateClientNotUniqueIdentifier()
    {
        $this->expectException(InvalidOptionException::class);
        $this->expectExceptionMessage('Option "identifier" is not unique.');

        $this->runCommand('sonrac_oauth:generate:client', [
            '--identifier' => 'test_client',
            '--name' => 'client_tester',
        ]);
    }

    /**
     * Test generate with invalid grant type option.
     */
    public function testGenerateClientWithInvalidGrantType()
    {
        $this->expectException(InvalidOptionException::class);
        $this->expectExceptionMessage('Option "grant-types" contains invalid value.');

        $this->runCommand('sonrac_oauth:generate:client', [
            '--identifier' => 'client_tester',
            '--name' => 'client_tester',
            '--grant-types' => 'invalid_grant',
        ]);
    }
}
