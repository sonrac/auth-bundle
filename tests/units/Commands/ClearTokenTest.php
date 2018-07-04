<?php

namespace sonrac\Auth\Tests\Units\Commands;

use sonrac\Auth\Tests\Units\BaseUnitTester;

/**
 * Class ClearTokenTest
 */
class ClearTokenTest extends BaseUnitTester
{
    /**
     * {@inheritdoc}
     */
    protected $seeds = ['clients', 'accessTokens', 'refreshTokens'];

    /**
     * Test delete access tokens.
     */
    public function testDeleteTokens(): void
    {
        $this->seeCountInDatabase(2, 'access_tokens');
        $output = $this->runCommand('sonrac_auth:clear:tokens');

        $this->assertContains('Drop access tokens.', $output);

        $this->seeCountInDatabase(2, 'access_tokens');
        $this->seeCountInDatabase(1, 'refresh_tokens');
    }

    /**
     * Test delete expired access tokens.
     */
    public function testDeleteExpiredTokens(): void
    {
        $output = $this->runCommand('sonrac_auth:clear:tokens', [
            '--with-expired' => true,
            '--with-refresh' => true
        ]);

        $this->assertContains('Drop access tokens.', $output);

        $this->seeCountInDatabase(1, 'access_tokens');
        $this->seeCountInDatabase(0, 'refresh_tokens');
    }

    /**
     * Test delete all access tokens.
     */
    public function testDeleteAllTokens(): void
    {
        $output = $this->runCommand('sonrac_auth:clear:tokens', [
            '--all' => true
        ]);

        $this->assertContains('Drop access tokens.', $output);

        $this->seeCountInDatabase(0, 'access_tokens');
        $this->seeCountInDatabase(1, 'refresh_tokens');
    }

    /**
     * Test delete access tokens.
     */
    public function testDeleteWithRefreshTokens(): void
    {
        $output = $this->runCommand('sonrac_auth:clear:tokens', [
            '--with-refresh' => true
        ]);

        $this->assertContains('Drop access tokens.', $output);
        $this->assertContains('Drop refresh tokens.', $output);

        $this->seeCountInDatabase(2, 'access_tokens');
        $this->seeCountInDatabase(1, 'refresh_tokens');
    }

    /**
     * Test delete all access tokens with refresh tokens.
     */
    public function testDeleteAllWithRefreshTokens(): void
    {
        $output = $this->runCommand('sonrac_auth:clear:tokens', [
            '--with-refresh' => true,
            '--all' => true
        ]);

        $this->assertContains('Drop access tokens.', $output);
        $this->assertContains('Drop refresh tokens.', $output);

        $this->seeCountInDatabase(0, 'access_tokens');
        $this->seeCountInDatabase(0, 'refresh_tokens');
    }
}
