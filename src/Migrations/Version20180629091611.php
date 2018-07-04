<?php

declare(strict_types=1);

namespace sonrac\Auth\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\AbstractMigration;
use sonrac\Auth\Entity\User;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180629091611 extends AbstractMigration
{
    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema): void
    {
        $users = $schema->createTable('users');

        $users->addColumn('id', Type::INTEGER)
            ->setNotnull(true)
            ->setAutoincrement(true);

        $users->addColumn('username', Type::INTEGER)
            ->setLength(500)
            ->setNotnull(true);
        $users->addColumn('email', Type::STRING)
            ->setLength(512)
            ->setNotnull(true);
        $users->addColumn('password', Type::STRING)
            ->setNotnull(true)
            ->setLength(2000);
        $users->addColumn('first_name', Type::STRING)
            ->setNotnull(true)
            ->setLength(255);
        $users->addColumn('last_name', Type::STRING)
            ->setNotnull(true)
            ->setLength(255);
        $users->addColumn('middle_name', Type::STRING)
            ->setNotnull(true)
            ->setDefault('')
            ->setLength(255);
        $users->addColumn('avatar', Type::STRING)
            ->setNotnull(false)
            ->setLength(2000);
        $users->addColumn('api_token', Type::STRING)
            ->setNotnull(false)
            ->setLength(2000);
        $users->addColumn('roles', Type::STRING)
            ->setLength(2000)
            ->setNotnull(false)
            ->setDefault(User::ROLE_USER);
        $users->addColumn('birthday', Type::BIGINT)
            ->setLength(20)
            ->setNotnull(false);
        $users->addColumn('additional_permissions', Type::TEXT)
            ->setNotnull(false)
            ->setDefault('');

        foreach (['api_token_expire_at', 'last_login', 'created_at', 'updated_at'] as $columnName) {
            $notNull = $columnName !== 'created_at';
            $users->addColumn($columnName, Type::BIGINT)
                ->setLength(20)
                ->setNotnull(!$notNull);
        }

        $users->setPrimaryKey(['id']);
    }

    /**
     * {@inheritdoc}
     */
    public function down(Schema $schema): void
    {
        $schema->dropTable('users');
    }
}
