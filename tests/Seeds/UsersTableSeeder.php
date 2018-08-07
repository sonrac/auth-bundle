<?php

declare(strict_types=1);

namespace sonrac\Auth\Tests\Seeds;

use sonrac\SimpleSeed\RollBackSeedWithCheckExists;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

/**
 * Class UsersTableSeeder.
 */
class UsersTableSeeder extends RollBackSeedWithCheckExists
{
    /**
     * {@inheritdoc}
     */
    protected function getTable(): string
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    protected function getData(): array
    {
        return [
            [
                'username'   => 'username',
                'first_name' => 'John',
                'last_name'  => 'Doe',
                'email'      => 'email@email.com',
                'password'   => (new BCryptPasswordEncoder(13))->encodePassword('password', null),
                'created_at' => \time(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getDeleteFields($data): array
    {
        return $this->getWhereForRow($data);
    }

    /**
     * {@inheritdoc}
     */
    protected function getWhereForRow($data): array
    {
        return ['username' => $data['username']];
    }
}
