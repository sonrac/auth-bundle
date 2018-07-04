<?php

namespace sonrac\Auth\Tests\Seeds;

use sonrac\SimpleSeed\RollBackSeedWithCheckExists;

/**
 * Class AuthCodesTableSeeder
 */
class AuthCodesTableSeeder extends RollBackSeedWithCheckExists
{
    /**
     * @inheritDoc
     */
    protected function getTable(): string
    {
        return 'auth_codes';
    }

    /**
     * @inheritDoc
     */
    protected function getData()
    {
        return [
            [
                'code'          => 'test_code',
                'redirect_uris' => 'test',
                'scopes'        => 'client|admin',
                'client_id'     => 'Test Client',
                'user_id'       => 1,
                'expire_at'     => time(),
                'created_at'    => time(),
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    protected function checkDeleted($data): array
    {
        return $this->getWhereForRow($data);
    }

    /**
     * @inheritDoc
     */
    protected function getWhereForRow($data): array
    {
        return ['code' => $data['code']];
    }


}