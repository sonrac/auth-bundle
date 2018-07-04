<?php

namespace sonrac\Auth\Tests\Seeds;

use sonrac\Auth\Entity\Client;
use sonrac\SimpleSeed\RollBackSeedWithCheckExists;

/**
 * Class AccessTokensTableSeeder
 */
class AccessTokensTableSeeder extends RollBackSeedWithCheckExists
{
    /**
     * @inheritDoc
     */
    protected function getTable(): string
    {
        return 'access_tokens';
    }

    /**
     * @inheritDoc
     */
    protected function getData()
    {
        return [
            [
                'token'      => 'test_token',
                'grant_type' => Client::GRANT_CLIENT_CREDENTIALS,
                'client_id'  => 'Test Client',
                'expire_at'  => time(),
                'created_at' => time()
            ],
            [
                'token'      => 'test_token1',
                'grant_type' => Client::GRANT_CLIENT_CREDENTIALS,
                'client_id'  => 'Test Client',
                'expire_at'  => time() + 3600,
                'created_at' => time()
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getWhereForRow($data): array
    {
        return ['token' => $data['token']];
    }

    /**
     * @inheritDoc
     */
    protected function checkDeleted($data): array
    {
        return $this->getWhereForRow($data);
    }


}