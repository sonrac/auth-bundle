<?php

declare(strict_types=1);

namespace sonrac\Auth\Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Trait DatabaseTrait.
 */
trait DatabaseTrait
{
    /**
     * Console application.
     *
     * @var \Symfony\Bundle\FrameworkBundle\Console\Application
     */
    protected $app;

    /**
     * Clear tables list. Run delete * from table after each test.
     *
     * @var array
     */
    protected $clearTablesList = [];

    /**
     * Seeds.
     *
     * @var array
     */
    protected $seeds = [];

    /**
     * Seeds namespace.
     *
     * @var string
     */
    protected $seedNameSpace = 'sonrac\Auth\Tests\Seeds\\';

    /**
     * Setup database.
     */
    protected function setUpDatabase(): void
    {
        $this->runCommand('doctrine:migrations:migrate', [
            '--no-interaction',
        ]);

        if (empty($this->seeds)) {
            return;
        }

        foreach ($this->seeds as $seed) {
            $this->runCommand('seed:run', [
                '--class' => $this->getSeedClassName($seed),
            ]);
        }
    }

    /**
     * Get seed classname.
     *
     * @param string $class
     *
     * @return string
     */
    protected function getSeedClassName(string $class): string
    {
        $className = \class_exists($class) ? $class : $this->seedNameSpace.$class;

        return \class_exists($className) ? $className : $this->seedNameSpace.\ucfirst($class).'TableSeeder';
    }

    /**
     * Tear down database.
     */
    protected function tearDownDatabase(): void
    {
        if (!empty($this->clearTablesList)) {
            foreach ($this->clearTablesList as $table) {
                static::$container->get('doctrine.dbal.default_connection')
                    ->createQueryBuilder()
                    ->delete($table)
                    ->execute();
            }
        }
        if (empty($this->seeds)) {
            return;
        }

        foreach ($this->seeds as $seed) {
            $this->runCommand('seed:run', [
                '--class' => $this->getSeedClassName($seed),
                '--rollback',
            ]);
        }
    }

    /**
     * Run console command.
     *
     * @param string $commandName
     * @param array  $arguments
     *
     * @return string
     */
    protected function runCommand(string $commandName, array $arguments = []): ?string
    {
        $command = $this->getConsoleApp()->find($commandName);
        $tester  = new CommandTester($command);
        $tester->execute($arguments);

        return $tester->getDisplay();
    }

    /**
     * Get console application.
     *
     * @return \Symfony\Bundle\FrameworkBundle\Console\Application
     */
    public function getConsoleApp(): Application
    {
        if ($this->app) {
            return $this->app;
        }

        $kernel = static::bootKernel();

        return $this->app = new Application($kernel);
    }
}
