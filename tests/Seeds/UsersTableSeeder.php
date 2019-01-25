<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Tests\Seeds;

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
        return 'oauth2_users';
    }

    /**
     * {@inheritdoc}
     */
    protected function getData(): array
    {
        return [
            [
                'username'   => 'username',
                'email'      => 'email@email.com',
                'password'   => (new BCryptPasswordEncoder(12))->encodePassword('password', null),
                'roles'      => \json_encode(['ROLE_USER']),
                'first_name' => 'John',
                'last_name'  => 'Doe',
                'status'     => 'active',
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
