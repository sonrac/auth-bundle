<?php

namespace sonrac\Auth\Tests\Units;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class BaseUnitTester.
 */
abstract class BaseUnitTester extends KernelTestCase
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
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $kernel = static::bootKernel();

        $this->app = new Application($kernel);

        $this->setUpDatabase();
    }

    /**
     * Setup database.
     */
    protected function setUpDatabase(): void
    {
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
     * Run console command.
     *
     * @param string $commandName
     * @param array  $arguments
     *
     * @return string
     */
    protected function runCommand(string $commandName, array $arguments = []): ?string
    {
        $command = $this->app->find($commandName);
        $tester  = new CommandTester($command);
        $tester->execute($arguments);

        return $tester->getDisplay();
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
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        $this->tearDownDatabase();

        parent::tearDown();
    }

    /**
     * See count records in database.
     *
     * @param int    $count
     * @param string $table
     * @param array  $condition
     */
    protected function seeCountInDatabase(int $count, string $table, array $condition = []): void
    {
        $connection = static::$container->get('doctrine.dbal.default_connection');

        $query = $connection->createQueryBuilder()
//            ->select('*')
            ->select('count(*)')
            ->from($table, $table);

        if (!empty($condition)) {
            foreach ($condition as $name => $value) {
                $query->andWhere("{$name} = :{$name}")
                    ->setParameter($name, $value);
            }
        }

        $data = $query->execute()->fetchColumn();
//        $data = $query->execute()->fetchAll();
//        var_dump($data);
        $this->assertEquals($count, (int) $data);
    }
}
