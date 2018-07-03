<?php

namespace sonrac\Auth\Tests\Seeds;

use sonrac\SimpleSeed\SimpleSeedWithCheckExists;

/**
 * Class Clients
 */
class Clients extends SimpleSeedWithCheckExists
{
    /**
     * @inheritDoc
     */
    protected function getTable(): string
    {
        return 'clients';
    }

    /**
     * @inheritDoc
     */
    protected function getData(): array
    {
        return [
            [
                'name' => 'Test Client',
                'description' => 'First test client',
                'created_at' => time(),
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getWhereForRow($data): array
    {
        return ['name' => $data['name']];
    }

}