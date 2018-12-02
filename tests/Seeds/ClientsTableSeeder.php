<?php

declare(strict_types=1);

namespace sonrac\Auth\Tests\Seeds;

use Sonrac\OAuth2\Adapter\League\Grant\AuthCodeGrant;
use Sonrac\OAuth2\Adapter\League\Grant\ClientCredentialsGrant;
use Sonrac\OAuth2\Adapter\League\Grant\ImplicitGrant;
use Sonrac\OAuth2\Adapter\League\Grant\PasswordGrant;
use Sonrac\OAuth2\Adapter\League\Grant\RefreshTokenGrant;
use sonrac\SimpleSeed\RollBackSeedWithCheckExists;

/**
 * Class Clients.
 */
class ClientsTableSeeder extends RollBackSeedWithCheckExists
{
    /**
     * {@inheritdoc}
     */
    protected function getTable(): string
    {
        return 'clients';
    }

    /**
     * {@inheritdoc}
     */
    protected function getData(): array
    {
        return [
            [
                'name' => 'Test Client',
                'description' => 'First test client',
                'secret' => 'secret-key',
                'created_at' => \time(),
                'allowed_grant_types' => \json_encode([
                    ClientCredentialsGrant::TYPE,
                    AuthCodeGrant::TYPE,
                    ImplicitGrant::TYPE,
                    PasswordGrant::TYPE,
                    RefreshTokenGrant::TYPE,
                ]),
                'redirect_uris' => \json_encode([
                    'http://test.com',
                    'https://test.com',
                ]),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getWhereForRow($data): array
    {
        return ['name' => $data['name']];
    }

    /**
     * {@inheritdoc}
     */
    protected function getDeleteFields($data): array
    {
        return $this->getWhereForRow($data);
    }
}
