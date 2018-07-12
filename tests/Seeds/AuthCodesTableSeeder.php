<?php

namespace sonrac\Auth\Tests\Seeds;

use sonrac\SimpleSeed\RollBackSeedWithCheckExists;

/**
 * Class AuthCodesTableSeeder.
 */
class AuthCodesTableSeeder extends RollBackSeedWithCheckExists
{
    /**
     * {@inheritdoc}
     */
    protected function getTable(): string
    {
        return 'auth_codes';
    }

    /**
     * {@inheritdoc}
     */
    protected function getData()
    {
        return [
            [
                'code'         => 'test_code',
                'redirect_uri' => 'test',
                'token_scopes' => json_encode(['client', 'admin']),
                'client_id'    => 'Test Client',
                'user_id'      => 1,
                'expire_at'    => \time(),
                'created_at'   => \time(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function checkDeleted($data): array
    {
        return $this->getWhereForRow($data);
    }

    /**
     * {@inheritdoc}
     */
    protected function getWhereForRow($data): array
    {
        return ['code' => $data['code']];
    }
}
