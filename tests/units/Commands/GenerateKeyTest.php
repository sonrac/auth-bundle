<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Tests\Units\Commands;

use Sonrac\OAuth2\Tests\Units\BaseUnitTester;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class GenerateKeyTest
 * @package Sonrac\OAuth2\Tests\Units\Commands
 */
class GenerateKeyTest extends BaseUnitTester
{
    /**
     * Command tester.
     *
     * @var \Symfony\Component\Console\Tester\CommandTester
     */
    private $commandTester;

    /**
     * Key path.
     *
     * @var string
     */
    private $keyPath;

    /**
     * Command.
     *
     * @var \Symfony\Component\Console\Tester\CommandTester
     */
    private $command;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->keyPath = __DIR__ . '/../../app/resources/keys/';

        foreach (['pub.key', 'priv.key'] as $file) {
            @\unlink($this->keyPath . $file);
        }

        $this->command = $this->getConsoleApp()->find('sonrac_oauth:generate:keys');
        $this->commandTester = new CommandTester($this->command);
    }

    /**
     * Test generate keys.
     */
    public function testGenerateFirst(): void
    {
        foreach (['pub.key', 'priv.key'] as $file) {
            static::assertFileNotExists($this->keyPath . $file);
        }

        $output = $this->runCommand('sonrac_oauth:generate:keys');

        $this->assertContains('generated', $output);

        foreach (['pub.key', 'priv.key'] as $file) {
            static::assertFileExists($this->keyPath . $file);
        }

        $this->checkKeys();
    }

    /**
     * Regenerate keys with pass phrase.
     */
    public function testRegenerateWithPhrase(): void
    {
        $this->testGenerateForce(true);
    }

    /**
     * Test force generate.
     *
     * @param bool $withPhrase
     */
    public function testGenerateForce(bool $withPhrase = false): void
    {
        foreach (['pub.key', 'priv.key'] as $file) {
            static::assertFileNotExists($this->keyPath . $file);
        }

        $output = $this->runCommand('sonrac_oauth:generate:keys');

        $this->assertContains('generated', $output);

        $contents = [];

        $dir = $this->keyPath;
        foreach (['pub.key', 'priv.key'] as $file) {
            static::assertFileExists($dir . $file);
            $contents[$dir . $file] = \file_get_contents($dir . $file);
        }

        $this->checkKeys();

        $this->expectExceptionMessage('Key pair is already generated.');
        $this->runCommand('sonrac_oauth:generate:keys');

        $arguments = ['--force' => true];

        if ($withPhrase) {
            $arguments['--passphrase'] = 123;
        }

        $output = $this->runCommand('sonrac_oauth:generate:keys', $arguments);
        $this->assertContains('generated', $output);

        foreach (['pub.key', 'priv.key'] as $file) {
            static::assertFileExists($dir . $file);
            static::assertNotEquals($contents[$dir . $file], \file_get_contents($dir . $file));
        }

        $this->checkKeys($withPhrase ? '123' : null);
    }

    /**
     * Check correct keys.
     *
     * @param string|null $phrase
     */
    protected function checkKeys(?string $phrase = null): void
    {
        $key = @\openssl_pkey_get_public(\file_get_contents($this->keyPath . 'pub.key'));
        $this->assertNotEmpty($key);
        $key = @\openssl_pkey_get_private(\file_get_contents($this->keyPath . 'priv.key'), $phrase ?? '');
        $this->assertNotEmpty($key);
    }
}
