<?php

namespace sonrac\Auth\Tests\Seeds;

use sonrac\Auth\Entity\Client;
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
                'name'                => 'Test Client',
                'description'         => 'First test client',
                'secret'              => 'secret-key',
                'created_at'          => \time(),
                'allowed_grant_types' => \json_encode([
                    Client::GRANT_CLIENT_CREDENTIALS,
                    Client::GRANT_PASSWORD,
                    Client::GRANT_IMPLICIT,
                    Client::GRANT_AUTH_CODE,
                    Client::GRANT_REFRESH_TOKEN,
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
