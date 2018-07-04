<?php

namespace sonrac\Auth\Tests\Units\Commands;

use sonrac\Auth\Tests\Units\BaseUnitTester;

/**
 * Class GenerateClientTest.
 */
class GenerateClientTest extends BaseUnitTester
{
    /**
     * {@inheritdoc}
     */
    protected $clearTablesList = ['clients'];

    /**
     * Test generate client.
     */
    public function testGenerateClient(): void
    {
        $output = $this->runCommand('sonrac_auth:generate:client', [
           '--name'        => 'client_tester',
           '--description' => 'Test application',
        ]);

        $this->seeCountInDatabase(1, 'clients', ['name' => 'client_tester']);

        $this->assertContains('Client ID:', $output);
        $this->assertContains('Client secret:', $output);
    }
}
