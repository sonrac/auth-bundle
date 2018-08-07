<?php

declare(strict_types=1);

namespace sonrac\Auth\Tests\Functional;

use sonrac\Auth\Tests\DatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class BaseFunctionalTester.
 */
class BaseFunctionalTester extends WebTestCase
{
    use DatabaseTrait;

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $this->tearDownDatabase();

        parent::tearDown();
    }

    /**
     * {@inheritdoc}
     */
    protected static function createClient(array $options = [], array $server = [])
    {
        $kernel = static::bootKernel($options);

        $client = $kernel->getContainer()->get('test.client');
        $client->setServerParameters($server);

        $client->insulate(false);

        return $client;
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();

        $this->runCommand('sonrac_auth:generate:keys');

        $this->setUpDatabase();
    }
}
