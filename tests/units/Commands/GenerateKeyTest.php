<?php

namespace sonrac\Auth\Tests\Units\Commands;

use sonrac\Auth\Tests\Units\BaseUnitTester;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class GenerateKeyTest.
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
     * Test generate keys.
     */
    public function testGenerateFirst(): void
    {
        foreach (['pub.key', 'priv.key'] as $file) {
            static::assertFileNotExists($this->keyPath.$file);
        }

        $output = $this->runCommand('sonrac_auth:generate:keys');

        $this->assertContains('generated', $output);

        foreach (['pub.key', 'priv.key'] as $file) {
            static::assertFileExists($this->keyPath.$file);
        }

        $this->checkKeys();
    }

    /**
     * Check correct keys.
     *
     * @param null $phrase
     */
    protected function checkKeys($phrase = null): void
    {
        $key = @\openssl_pkey_get_public(\file_get_contents($this->keyPath.'pub.key'));
        $this->assertNotEmpty($key);
        $key = @\openssl_pkey_get_private(\file_get_contents($this->keyPath.'priv.key'), $phrase);
        $this->assertNotEmpty($key);
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
    public function testGenerateForce($withPhrase = null): void
    {
        foreach (['pub.key', 'priv.key'] as $file) {
            static::assertFileNotExists($this->keyPath.$file);
        }

        $output = $this->runCommand('sonrac_auth:generate:keys');

        $this->assertContains('generated', $output);

        $contents = [];

        $dir = $this->keyPath;
        foreach (['pub.key', 'priv.key'] as $file) {
            static::assertFileExists($dir.$file);
            $contents[$dir.$file] = \file_get_contents($dir.$file);
        }

        $this->checkKeys();

        $output = $this->runCommand('sonrac_auth:generate:keys');
        $this->assertEmpty($output);

        $arguments = [
            '--force' => null,
        ];

        if ($withPhrase) {
            $arguments['--passphrase'] = 123;
        }

        $output = $this->runCommand('sonrac_auth:generate:keys', $arguments);
        $this->assertContains('generated', $output);

        foreach (['pub.key', 'priv.key'] as $file) {
            static::assertFileExists($dir.$file);
            static::assertNotEquals($contents[$dir.$file], \file_get_contents($dir.$file));
        }

        $this->checkKeys($withPhrase ? 123 : null);
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->keyPath = __DIR__.'/../../app/resources/keys/';

        foreach (['pub.key', 'priv.key'] as $file) {
            @\unlink($this->keyPath.$file);
        }

        $this->command       = $this->getConsoleApp()->find('sonrac_auth:generate:keys');
        $this->commandTester = new CommandTester($this->command);
    }
}
