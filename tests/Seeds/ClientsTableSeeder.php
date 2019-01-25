<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Tests\Seeds;

use Sonrac\OAuth2\Bridge\Grant\AuthCodeGrant;
use Sonrac\OAuth2\Bridge\Grant\ClientCredentialsGrant;
use Sonrac\OAuth2\Bridge\Grant\ImplicitGrant;
use Sonrac\OAuth2\Bridge\Grant\PasswordGrant;
use Sonrac\OAuth2\Bridge\Grant\RefreshTokenGrant;
use sonrac\SimpleSeed\RollBackSeedWithCheckExists;

/**
 * Class ClientsTableSeeder.
 */
class ClientsTableSeeder extends RollBackSeedWithCheckExists
{
    /**
     * {@inheritdoc}
     */
    protected function getTable(): string
    {
        return 'oauth2_clients';
    }

    /**
     * {@inheritdoc}
     */
    protected function getData(): array
    {
        return [
            [
                'id'                  => 'test_client',
                'name'                => 'Test Client',
                'description'         => 'First test client',
                'secret'              => 'secret-key',
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
                'created_at' => \time(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getWhereForRow($data): array
    {
        return ['id' => $data['id']];
    }

    /**
     * {@inheritdoc}
     */
    protected function getDeleteFields($data): array
    {
        return $this->getWhereForRow($data);
    }
}
