<?php

namespace sonrac\Auth\Tests\Units;

use sonrac\Auth\Tests\DatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class BaseUnitTester.
 */
abstract class BaseUnitTester extends KernelTestCase
{
    use DatabaseTrait;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
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
            ->select('count(*)')
            ->from($table, $table);

        if (!empty($condition)) {
            foreach ($condition as $name => $value) {
                $query->andWhere("{$name} = :{$name}")
                    ->setParameter($name, $value);
            }
        }

        $data = $query->execute()->fetchColumn();
        $this->assertEquals($count, (int) $data);
    }
}
